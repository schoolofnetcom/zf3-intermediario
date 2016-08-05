<?php


namespace Blog\Model;

use Zend\Db\TableGateway\Exception\RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class CommentTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($post_id)
    {
        return $this->tableGateway->select([
            'post_id' => $post_id
        ]);
    }

    public function save(Comment $comment)
    {
        $data = [
            'content' => $comment->content,
            'post_id' => $comment->post_id
        ];
        $this->tableGateway->insert($data);
        return;
    }

}