<?php


namespace Blog\Controller;


use Blog\Form\PostForm;
use Blog\Model\Post;
use Blog\Model\PostTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    /**
     * @var PostTable
     */
    private $table;
    /**
     * @var PostForm
     */
    private $form;

    public function __construct(PostTable $table, PostForm $form)
    {
        $this->table = $table;
        $this->form = $form;
    }

    public function indexAction()
    {
        $postTable = $this->table;

        return new ViewModel([
            'posts' => $postTable->fetchAll()
        ]);
    }

    public function addAction()
    {
        $form = $this->form;
        $form->get('submit')->setValue('Add Post');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $post = new Post();
        $post->exchangeArray($form->getData());
        $this->table->save($post);
        return $this->redirect()->toRoute('admin-blog/post');
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('admin-blog/post');
        }

        try {
            $post = $this->table->find($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin-blog/post');
        }

        $form = $this->form;
        $form->bind($post);
        $form->get('submit')->setAttribute('value', 'Edit Post');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return [
                'id' => $id,
                'form' => $form
            ];
        }

        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return [
                'id' => $id,
                'form' => $form
            ];
        }

        $this->table->save($post);
        return $this->redirect()->toRoute('admin-blog/post');
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-blog/post');
        }

        $this->table->delete($id);
        return $this->redirect()->toRoute('admin-blog/post');
        
    }

}