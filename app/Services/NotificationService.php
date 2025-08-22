<?php

namespace App\Services;

use App\Models\NotificationSubscription;
use App\Models\Materia;
use App\Models\Edicao;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notifica inscritos sobre nova matéria
     */
    public function notifyNewMateria(Materia $materia)
    {
        $materia->load(['tipo', 'orgao']);
        
        $subscriptions = NotificationSubscription::active()
            ->verified()
            ->get()
            ->filter(function ($subscription) use ($materia) {
                return $subscription->shouldNotify($materia);
            });

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendNotification($subscription, $materia);
                $subscription->markNotificationSent();
            } catch (\Exception $e) {
                Log::error('Erro ao enviar notificação', [
                    'subscription_id' => $subscription->id,
                    'materia_id' => $materia->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Notifica inscritos sobre nova edição
     */
    public function notifyNewEdicao(Edicao $edicao)
    {
        $edicao->load(['materias.tipo', 'materias.orgao']);
        
        $subscriptions = NotificationSubscription::active()
            ->verified()
            ->get();

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendEdicaoNotification($subscription, $edicao);
                $subscription->markNotificationSent();
            } catch (\Exception $e) {
                Log::error('Erro ao enviar notificação de edição', [
                    'subscription_id' => $subscription->id,
                    'edicao_id' => $edicao->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Envia notificação individual
     */
    private function sendNotification(NotificationSubscription $subscription, Materia $materia)
    {
        switch ($subscription->type) {
            case 'email':
                $this->sendEmailNotification($subscription, $materia);
                break;
                
            case 'whatsapp':
                $this->sendWhatsAppNotification($subscription, $materia);
                break;
                
            case 'both':
                $this->sendEmailNotification($subscription, $materia);
                $this->sendWhatsAppNotification($subscription, $materia);
                break;
        }
    }

    /**
     * Envia notificação de edição
     */
    private function sendEdicaoNotification(NotificationSubscription $subscription, Edicao $edicao)
    {
        switch ($subscription->type) {
            case 'email':
                $this->sendEdicaoEmailNotification($subscription, $edicao);
                break;
                
            case 'whatsapp':
                $this->sendEdicaoWhatsAppNotification($subscription, $edicao);
                break;
                
            case 'both':
                $this->sendEdicaoEmailNotification($subscription, $edicao);
                $this->sendEdicaoWhatsAppNotification($subscription, $edicao);
                break;
        }
    }

    /**
     * Envia notificação por email
     */
    private function sendEmailNotification(NotificationSubscription $subscription, Materia $materia)
    {
        $data = [
            'subscription' => $subscription,
            'materia' => $materia,
            'unsubscribe_url' => $subscription->unsubscribe_url
        ];

        Mail::send('emails.nova-materia', $data, function ($message) use ($subscription, $materia) {
            $message->to($subscription->email, $subscription->name)
                    ->subject("Nova Publicação: {$materia->titulo}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }

    /**
     * Envia notificação de edição por email
     */
    private function sendEdicaoEmailNotification(NotificationSubscription $subscription, Edicao $edicao)
    {
        $data = [
            'subscription' => $subscription,
            'edicao' => $edicao,
            'unsubscribe_url' => $subscription->unsubscribe_url
        ];

        Mail::send('emails.nova-edicao', $data, function ($message) use ($subscription, $edicao) {
            $message->to($subscription->email, $subscription->name)
                    ->subject("Nova Edição do Diário Oficial: {$edicao->numero}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }

    /**
     * Envia notificação por WhatsApp
     */
    private function sendWhatsAppNotification(NotificationSubscription $subscription, Materia $materia)
    {
        if (!$subscription->phone) {
            return;
        }

        $message = $this->buildWhatsAppMessage($materia);
        $this->sendWhatsAppMessage($subscription->phone, $message);
    }

    /**
     * Envia notificação de edição por WhatsApp
     */
    private function sendEdicaoWhatsAppNotification(NotificationSubscription $subscription, Edicao $edicao)
    {
        if (!$subscription->phone) {
            return;
        }

        $message = $this->buildEdicaoWhatsAppMessage($edicao);
        $this->sendWhatsAppMessage($subscription->phone, $message);
    }

    /**
     * Constrói mensagem do WhatsApp para matéria
     */
    private function buildWhatsAppMessage(Materia $materia)
    {
        $url = route('portal.materias.show', $materia);
        
        return "🏛️ *Nova Publicação no Diário Oficial*\n\n" .
               "📋 *Título:* {$materia->titulo}\n" .
               "📁 *Tipo:* {$materia->tipo->nome}\n" .
               "🏢 *Órgão:* {$materia->orgao->nome}\n" .
               "📅 *Data:* {$materia->data->format('d/m/Y')}\n\n" .
               "🔗 Acesse: {$url}\n\n" .
               "_Para cancelar estas notificações, responda PARAR_";
    }

    /**
     * Constrói mensagem do WhatsApp para edição
     */
    private function buildEdicaoWhatsAppMessage(Edicao $edicao)
    {
        $url = route('portal.edicoes.show', $edicao);
        
        return "📰 *Nova Edição do Diário Oficial*\n\n" .
               "📄 *Edição:* {$edicao->numero}\n" .
               "📅 *Data:* {$edicao->data->format('d/m/Y')}\n" .
               "📊 *Total de Matérias:* {$edicao->materias->count()}\n\n" .
               "🔗 Acesse: {$url}\n\n" .
               "_Para cancelar estas notificações, responda PARAR_";
    }

    /**
     * Envia mensagem via WhatsApp (usando API externa)
     */
    private function sendWhatsAppMessage($phone, $message)
    {
        // Aqui você integraria com uma API de WhatsApp como:
        // - WhatsApp Business API
        // - Twilio
        // - ChatAPI
        // - Evolution API
        
        $whatsappConfig = config('services.whatsapp');
        
        if (!$whatsappConfig || !$whatsappConfig['enabled']) {
            Log::info('WhatsApp não configurado, mensagem não enviada', [
                'phone' => $phone,
                'message_preview' => substr($message, 0, 100)
            ]);
            return;
        }

        try {
            // Exemplo usando Evolution API (ajuste conforme sua API)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $whatsappConfig['token'],
                'Content-Type' => 'application/json'
            ])->post($whatsappConfig['url'] . '/message/sendText/' . $whatsappConfig['instance'], [
                'number' => $this->formatPhoneNumber($phone),
                'text' => $message
            ]);

            if ($response->successful()) {
                Log::info('Mensagem WhatsApp enviada com sucesso', [
                    'phone' => $phone,
                    'response' => $response->json()
                ]);
            } else {
                Log::error('Erro ao enviar WhatsApp', [
                    'phone' => $phone,
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar WhatsApp', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Formata número de telefone para WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove caracteres não numéricos
        $phone = preg_replace('/\D/', '', $phone);
        
        // Adiciona código do Brasil se necessário
        if (strlen($phone) === 11 && substr($phone, 0, 1) !== '55') {
            $phone = '55' . $phone;
        }
        
        return $phone;
    }

    /**
     * Cria nova inscrição
     */
    public function createSubscription($email, $type = 'email', $preferences = null, $name = null, $phone = null)
    {
        $subscription = NotificationSubscription::create([
            'email' => $email,
            'phone' => $phone,
            'name' => $name,
            'type' => $type,
            'preferences' => $preferences,
            'active' => true
        ]);

        // Enviar email de verificação
        $this->sendVerificationEmail($subscription);

        return $subscription;
    }

    /**
     * Envia email de verificação
     */
    public function sendVerificationEmail(NotificationSubscription $subscription)
    {
        Mail::send('emails.verify-subscription', ['subscription' => $subscription], function ($message) use ($subscription) {
            $message->to($subscription->email)
                    ->subject('Confirme sua inscrição no Diário Oficial')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }

    /**
     * Envia link de gerenciamento por email
     */
    public function sendManagementLink(NotificationSubscription $subscription)
    {
        Mail::send('emails.management-link', ['subscription' => $subscription], function ($message) use ($subscription) {
            $message->to($subscription->email)
                    ->subject('Link de gerenciamento - Diário Oficial')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }

    /**
     * Verifica inscrição por token
     */
    public function verifySubscription($token)
    {
        $subscription = NotificationSubscription::where('token', $token)->first();
        
        if ($subscription && !$subscription->verified_at) {
            $subscription->markAsVerified();
            return $subscription;
        }
        
        return null;
    }

    /**
     * Cancela inscrição por token
     */
    public function unsubscribe($token)
    {
        $subscription = NotificationSubscription::where('token', $token)->first();
        
        if ($subscription) {
            $subscription->unsubscribe();
            return $subscription;
        }
        
        return null;
    }
}
