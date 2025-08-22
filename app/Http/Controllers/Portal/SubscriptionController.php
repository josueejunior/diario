<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\NotificationSubscription;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Página de inscrição para notificações
     */
    public function index()
    {
        return view('portal.subscriptions.index');
    }

    /**
     * Criar nova inscrição
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notify_new_editions' => 'boolean',
            'notify_new_materials' => 'boolean',
            'notify_types' => 'nullable|array',
            'notify_types.*' => 'integer|exists:tipos,id',
            'notify_organs' => 'nullable|array',
            'notify_organs.*' => 'integer|exists:orgaos,id',
            'keywords' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar se já existe inscrição com este email
        $existingSubscription = NotificationSubscription::where('email', $request->email)->first();
        
        if ($existingSubscription && $existingSubscription->is_verified) {
            return back()->with('warning', 'Este email já possui uma inscrição ativa. Use o link de gerenciamento enviado por email para fazer alterações.');
        }

        // Criar ou atualizar inscrição
        $subscription = NotificationSubscription::updateOrCreate(
            ['email' => $request->email],
            [
                'phone' => $request->phone,
                'notify_new_editions' => $request->boolean('notify_new_editions'),
                'notify_new_materials' => $request->boolean('notify_new_materials'),
                'notification_types' => $request->notify_types ? json_encode($request->notify_types) : null,
                'notification_organs' => $request->notify_organs ? json_encode($request->notify_organs) : null,
                'keywords' => $request->keywords,
                'verification_token' => Str::random(60),
                'is_verified' => false,
                'is_active' => true,
            ]
        );

        // Enviar email de verificação
        try {
            $this->notificationService->sendVerificationEmail($subscription);
            return back()->with('success', 'Inscrição criada com sucesso! Verifique seu email para confirmar a inscrição.');
        } catch (\Exception $e) {
            return back()->with('error', 'Inscrição criada, mas houve um erro ao enviar o email de verificação. Tente novamente.');
        }
    }

    /**
     * Verificar email
     */
    public function verify($token)
    {
        $subscription = NotificationSubscription::where('verification_token', $token)->first();

        if (!$subscription) {
            return view('portal.subscriptions.verify-error', [
                'message' => 'Token de verificação inválido ou expirado.'
            ]);
        }

        $subscription->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verification_token' => null,
        ]);

        return view('portal.subscriptions.verify-success', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Página de gerenciamento da inscrição
     */
    public function manage($token)
    {
        $subscription = NotificationSubscription::where('management_token', $token)->first();

        if (!$subscription || !$subscription->is_verified) {
            return view('portal.subscriptions.manage-error', [
                'message' => 'Token de gerenciamento inválido ou inscrição não verificada.'
            ]);
        }

        return view('portal.subscriptions.manage', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Atualizar preferências da inscrição
     */
    public function update(Request $request, $token)
    {
        $subscription = NotificationSubscription::where('management_token', $token)->first();

        if (!$subscription || !$subscription->is_verified) {
            return back()->with('error', 'Token inválido ou inscrição não verificada.');
        }

        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:20',
            'notify_new_editions' => 'boolean',
            'notify_new_materials' => 'boolean',
            'notify_types' => 'nullable|array',
            'notify_types.*' => 'integer|exists:tipos,id',
            'notify_organs' => 'nullable|array',
            'notify_organs.*' => 'integer|exists:orgaos,id',
            'keywords' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $subscription->update([
            'phone' => $request->phone,
            'notify_new_editions' => $request->boolean('notify_new_editions'),
            'notify_new_materials' => $request->boolean('notify_new_materials'),
            'notification_types' => $request->notify_types ? json_encode($request->notify_types) : null,
            'notification_organs' => $request->notify_organs ? json_encode($request->notify_organs) : null,
            'keywords' => $request->keywords,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Preferências atualizadas com sucesso!');
    }

    /**
     * Cancelar inscrição
     */
    public function unsubscribe($token)
    {
        $subscription = NotificationSubscription::where('management_token', $token)->first();

        if (!$subscription) {
            return view('portal.subscriptions.unsubscribe-error', [
                'message' => 'Token inválido.'
            ]);
        }

        $subscription->delete();

        return view('portal.subscriptions.unsubscribe-success');
    }

    /**
     * Reenviar email de verificação
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:notification_subscriptions,email'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $subscription = NotificationSubscription::where('email', $request->email)
            ->where('is_verified', false)
            ->first();

        if (!$subscription) {
            return back()->with('error', 'Nenhuma inscrição não verificada encontrada para este email.');
        }

        try {
            $this->notificationService->sendVerificationEmail($subscription);
            return back()->with('success', 'Email de verificação reenviado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao reenviar email de verificação. Tente novamente.');
        }
    }

    /**
     * Solicitar link de gerenciamento
     */
    public function requestManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:notification_subscriptions,email'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $subscription = NotificationSubscription::where('email', $request->email)
            ->where('is_verified', true)
            ->first();

        if (!$subscription) {
            return back()->with('error', 'Nenhuma inscrição verificada encontrada para este email.');
        }

        try {
            $this->notificationService->sendManagementLink($subscription);
            return back()->with('success', 'Link de gerenciamento enviado para seu email!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar link de gerenciamento. Tente novamente.');
        }
    }
}
