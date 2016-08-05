<?php


namespace Blog\Model;

use Zend\Db\TableGateway\Exception\RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class PostTable
{
    private $tableGateway;
    /**
     * @var CommentTable
     */
    private $commentTable;

    public function __construct(TableGatewayInterface $tableGateway, CommentTable $commentTable)
    {
        $this->tableGateway = $tableGateway;
        $this->commentTable = $commentTable;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function save(Post $post)
    {
        $data = [
            'title' => $post->title,
            'content' => $post->content
        ];

        $id = (int)$post->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->find($id)) {
            throw new RuntimeException(sprintf(
                'Could not retrieve the row %d', $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function find($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current(); //$row == Post

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not retrieve the row %d', $id
            ));
        }

        $rowsComment = $this->commentTable->fetchAll($row->id);
        $row->comments = iterator_to_array($rowsComment);

        return $row;
    }

    public function delete($id)
    {
        $this->tableGateway->delete(['id' => (int)$id]);
    }

}