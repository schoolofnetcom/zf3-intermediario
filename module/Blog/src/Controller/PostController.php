<?php


namespace Blog\Controller;


use Blog\Form\CommentForm;
use Blog\Model\Comment;
use Blog\Model\CommentTable;
use Blog\Model\PostTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PostController extends AbstractActionController
{
    /**
     * @var PostTable
     */
    private $table;
    /**
     * @var CommentTable
     */
    private $commentTable;

    public function __construct(PostTable $table, CommentTable $commentTable)
    {
        $this->table = $table;
        $this->commentTable = $commentTable;
    }

    public function indexAction()
    {
        $postTable = $this->table;

        return new ViewModel([
            'posts' => $postTable->fetchAll()
        ]);
    }

    public function showAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $commentForm = new CommentForm();
        if (!$id) {
            return $this->redirect()->toRoute('post');
        }

        try {
            $post = $this->table->find($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('post');
        }

        return new ViewModel([
            'post' => $post,
            'commentForm' => $commentForm
        ]);
    }

    public function addCommentAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('site-post');
        }
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute('site-post');
        } else {
            try {
                $post = $this->table->find($id);
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('site-post');
            }

            $commentForm = new CommentForm();
            $commentForm->setData($request->getPost());

            if (!$commentForm->isValid()) {
                return $this->redirect()->toRoute('site-post', ['action' => 'show', 'id' => $post->id]);
            }
            $data = $commentForm->getData();
            $data['post_id'] = $post->id;
            $comment = new Comment();
            $comment->exchangeArray($data);
            $this->commentTable->save($comment);
            return $this->redirect()->toRoute('site-post', ['action' => 'show', 'id' => $post->id]);
        }
    }

}