<?php

//Mail
function teko_mail($para, $asunto, $html, $archivos = []){
    $mail = new Nette\Mail\Message;
    $mail->setFrom(Tipsy\Tipsy::config()['mail']['from']);
    //Para
    if(is_array($para)){
        //From
        if(array_key_exists('from', $para)){
            $para['from'] = (is_array($para['from']) ? $para['from'] : $para['from'] = [$para['from']]);
            foreach($para['from'] as $correo){
                $mail->setFrom($correo);
            }
        }
        //To
        if(array_key_exists('to', $para)){
            $para['to'] = (is_array($para['to']) ? $para['to'] : $para['to'] = [$para['to']]);
            foreach($para['to'] as $correo){
                $mail->addTo($correo);
            }
        } else {
            return false;
        }
        //CC
        if(array_key_exists('cc', $para)){
            $para['cc'] = (is_array($para['cc']) ? $para['cc'] : $para['cc'] = [$para['cc']]);
            foreach($para['cc'] as $correo){
                $mail->addCc($correo);
            }
        }
        //BCC
        if(array_key_exists('bcc', $para)){
            $para['bcc'] = (is_array($para['bcc']) ? $para['bcc'] : $para['bcc'] = [$para['bcc']]);
            foreach($para['bcc'] as $correo){
                $mail->addBcc($correo);
            }
        }
    } else {
        $mail->addTo($para);
    }
    //Asunto
    $mail->setSubject($asunto);
    //Mensaje
    $mail->setHTMLBody($html);
    //Archvios Adjuntos
    if(count($archivos)){
        foreach($archivos as $archivo){
            $mail->addAttachment($archivo);
        }
    }
    $mailer = new Nette\Mail\SendmailMailer;
    return $mailer->send($mail);
}