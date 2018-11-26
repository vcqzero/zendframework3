<?php
namespace Api\Service;

use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Api\Service\WebsiteManager;

class Mailer 
{
    private $name;
    private $host;
    private $port;
    private $username;
    private $password;

    public function __construct()
    {
        $config=include WebsiteManager::PATH_API_CONFIG;
        $config= $config[WebsiteManager::API_MAIL];
        $this->name = $config['name'];
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }
    
    /**
     * get the mail server
     *
     * @return Smtp
     */
    public function getSmtpTransport()
    {
        //config smtp transport
        $SmtpTransport  = new Smtp();
        //get options
        $options        = new SmtpOptions();
        //set ConnectionClass
        $options->setConnectionClass('login');
        //set connectionConfig
        $connectionConfig = [
            'username' => $this->username,
            'password' => $this->password,
        ];
        $options->setConnectionConfig($connectionConfig);
        //set port
        $options->setPort($this->port);
        //set host
        $options->setHost($this->host);
        //set name
        $options->setName($this->name);
        $SmtpTransport->setOptions($options);
        return $SmtpTransport;
    }
    
    public function sendEmailOnAddUser($userEmail, $username, $password)
    {
        $message = new Message();
        
        //邮件主题
        $websit_title = $this->WebsiteManager->getBasicParam('website_title');
        $subject = "用户创建成果！-$websit_title";
        $message->setSubject($subject);
        
        //收件人 发件人
        $message->setTo($userEmail);
        $sender = $this->WebsiteManager->getEmailParam('username');
        $message->setFrom($sender);
        
        //邮件主题内容
        $htmlMarkup  = "<h2>新增用户成功！</h2>";
        $htmlMarkup .= "<h3>用户名：$username</h3>";
        $htmlMarkup .= "<h3>密码：$password</h3>";
        $htmlMarkup .= "<p>请将以上用户名和密码转发给用户。</p>";
        $html = new MimePart($htmlMarkup);
        $html->type = Mime::TYPE_HTML;
        $html->charset = 'utf-8';
        $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $message->setEncoding('UTF-8');
        $body = new MimeMessage();
        $body->setParts([$html]);
        $message ->setBody($body);
        
        //发送邮件
        $stmp = $this->getStmp();
        $stmp->send($message);
    }
    
    public function sendEmailOnTest()
    {
        $message = new Message();
        
        //邮件主题
        $websit_title = $this->WebsiteManager->getBasicParam('website_title');
        $subject = "邮件设置成果！-$websit_title";
        $message->setSubject($subject);
        
        //收件人
        $userEmail = $this->WebsiteManager->getEmailParam('test_address');
        $message->setTo($userEmail);
        $sender = $this->WebsiteManager->getEmailParam('username');
        $message->setFrom($sender);
        
        //邮件主题内容
        $htmlMarkup = "<p>邮件服务器设置成果！</p>";
        $html = new MimePart($htmlMarkup);
        $html->type = Mime::TYPE_HTML;
        $html->charset = 'utf-8';
        $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $message->setEncoding('UTF-8');
        $body = new MimeMessage();
        $body->setParts([$html]);
        $message ->setBody($body);
        
        //发送邮件
        $stmp = $this->getStmp();
        $stmp->send($message);
    }
}
