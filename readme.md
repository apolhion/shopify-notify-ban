# Configuração do Sistema de Notificações

Este guia orienta você a configurar notificações automáticas usando hospedagem na Hostinger, um domínio próprio e o serviço de notificações Pushcut com assinatura ativa.

## Requisitos

1. **Hospedagem**: Hostinger ou similar.
2. **Domínio**: Seu próprio domínio configurado.
3. **Pushcut**: Conta com assinatura ativa para envio de notificações.

## Passo a Passo

### Passo 1: Configurar Notificação no Pushcut

1. Acesse sua conta no [Pushcut](https://www.pushcut.io/) e configure uma nova notificação.
2. Defina as configurações conforme as imagens abaixo:

   ![Configuração de notificação no Pushcut](https://i.imgur.com/dhKwB5q.jpeg)
   ![Detalhes da configuração no Pushcut](https://i.imgur.com/3WVfhPe.jpeg)

### Passo 2: Configurar Webhook no Arquivo PHP

1. Obtenha o código disponível no arquivo `index.php` deste repositório.
2. Na sua conta da Hostinger:
   - Acesse **Sites** > **Site de sua escolha** > **Gerenciador de Arquivos** > **public_html**.
   - Crie um novo arquivo PHP com o nome desejado, como `notificacao.php`, e cole o código do `index.php` neste arquivo.
3. Personalize a URL de webhook do Pushcut:
   - Substitua o link do webhook pelo seu.
   - Caso deseje enviar notificações para múltiplos dispositivos, descomente as linhas de código relevantes para outras notificações.

### Passo 3: Configurar Tarefas Cron

1. Crie uma conta em [cron-job.org](https://cron-job.org/) para agendar as execuções.
2. Acesse [https://console.cron-job.org/jobs](https://console.cron-job.org/jobs).
3. Clique em **CREATE CRONJOB** e configure o cron job para executar seu arquivo PHP conforme a periodicidade desejada.

   ![Configuração de cron job](https://i.imgur.com/4qm7T1s.png)

### Manutenção e Atualizações

Para adicionar, remover lojas ou editar apelidos, acesse o arquivo PHP criado. Instruções específicas estão comentadas no código, facilitando as modificações.

