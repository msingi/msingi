<?php

namespace Msingi\Cms\Mailer;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Msingi\Util\HTML2Text;
use Zend\Mail;
use Zend\Mime;
use Zend\Mail\Transport\Sendmail;

/**
 * Class Mailer
 *
 * @package Msingi\Cms\Mailer
 */
class Mailer implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param string $templateName
     * @param string $email
     * @param array $params
     * @return boolean
     */
    public function sendMail($templateName, $email, array $params = array())
    {
        $config = $this->serviceManager->get('Config');
        $settings = $this->serviceManager->get('Settings');

        if (!isset($config['mailer']['templates_path'])) {
            throw new \Exception('Mail templates path is not set');
        }

        // get from addresss
        if (isset($params['from'])) {
            $from = $params['from'];
        } else {
            $from = $settings->get('mail:from');
        }

        if ($from == '') {
            throw new \Exception('Can\'t send mail - from address not given');
        }

        // get language
        if (!isset($params['language']) || $params['language'] == '') {
            throw new \Exception('Can\'t send mail - language not given');
        }

        $language = $params['language'];

        // get mail template
        $templatesTable = $this->serviceManager->get('Msingi\Cms\Db\Table\MailTemplates');
        $template = $templatesTable->fetchOrCreate($templateName, $language);

        // replace tokens
        $subject = $this->processTokens($template->subject, $params);
        $message = $this->processTokens($template->template, $params);

        // initialize renderer
        $renderer = new PhpRenderer();
        $resolver = new Resolver\AggregateResolver();
        $renderer->setResolver($resolver);

        $stack = new Resolver\TemplatePathStack(array(
            'script_paths' => array(
                $config['mailer']['templates_path']
            )
        ));
        $resolver->attach($stack);

        // render message template
        $messageHtml = $renderer->render('default', array(
            'content' => $message
        ));

        // get text content of the message
        $html2text = new HTML2Text();
        $html2text->html2text($messageHtml);

        // create text message part
        $messageTextPart = new Mime\Part($html2text->get_text());
        $messageTextPart->type = 'text/plain';

        // create html message part
        $messageHtmlPart = new Mime\Part($messageHtml);
        $messageHtmlPart->type = 'text/html';

        // create message body
        $messageBody = new Mime\Message();
        $messageBody->setParts(array($messageTextPart, $messageHtmlPart));

        // @todo Implement attachements

        //
        $mail = new Mail\Message();

        $mail->setFrom($from);
        if (isset($params['reply-to']) && $params['reply-to'] != '') {
            $mail->setReplyTo($params['reply-to']);
        }
        $mail->addTo($email);
        $mail->setEncoding('UTF-8');
        $mail->setSubject($subject);
        $mail->setBody($messageBody);
        $mail->getHeaders()->get('Content-Type')->setType('multipart/alternative');

        // log message
        if ($settings->get('mail:log')) {
            $transport = new \Zend\Mail\Transport\File();
            $options = new \Zend\Mail\Transport\FileOptions(array(
                'path' => $config['mailer']['log_dir'],
                'callback' => function (\Zend\Mail\Transport\File $transport) use ($templateName) {
                        return date('Ymd.His') . '-' . $templateName . '-' . mt_rand() . '.txt';
                    },
            ));
            $transport->setOptions($options);
            $transport->send($mail);
        }

        // send message
        if ($settings->get('mail:send')) {
            $transport = new Sendmail();
            $transport->send($mail);
        }

        return true;
    }

    /**
     * @param string $text
     * @param array $replacements
     * @return string
     */
    protected function processTokens($text, array $replacements = array())
    {
        if (preg_match_all('/%([A-Z0-9\-_]+)%/i', $text, $matches)) {
            foreach ($matches[0] as $token) {
                $keyword = strtolower(trim($token, '%'));

                $replacement = isset($replacements[$keyword]) ? $replacements[$keyword] : '';

                $text = str_replace($token, $replacement, $text);
            }
        }

        return $text;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}