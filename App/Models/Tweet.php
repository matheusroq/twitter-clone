<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model{
    private $id;
    private $id_usuraio;
    private $tweet;
    private $data;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function salvar() {
        $query = 'insert into tweets(id_usuario, tweet)values(:id_usuario, :tweet)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();
        return $this;
    }

    public function getAll() {
        $query = 'select 
                t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data
            from 
                tweets as t
                left join usuarios as u on (t.id_usuario = u.id)
            where 
                t.id_usuario = :id_usuario
                or t.id_usuario in (select id_usuario_seguind from usuarios_seguidores where id_usuario = :id_usuario)
            order by 
                t.data DESC
                ';
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function removerTweet($id_tweet) {
        $query = 'delete from tweets where id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id_tweet);
        $stmt->execute();
        return true;
    }
}