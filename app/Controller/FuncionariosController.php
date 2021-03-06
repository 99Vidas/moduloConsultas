<?php
App::uses('AppController', 'Controller');
/**
 * Funcionarios Controller
 *
 * @property Funcionario $Funcionario
 * @property PaginatorComponent $Paginator
 */
class FuncionariosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Funcionario->recursive = 0;
		$this->set('funcionarios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Funcionario->exists($id)) {
			throw new NotFoundException(__('Invalid funcionario'));
		}
		$options = array('conditions' => array('Funcionario.' . $this->Funcionario->primaryKey => $id));
		$this->set('funcionario', $this->Funcionario->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if ($this->Funcionario->saveAll($this->request->data)) {
				$this->loadModel('Pessoa');
				$this->loadModel('User');

				$this->Funcionario->create();

				$ultimaPessoa=$this->Pessoa->find('first', array('order' => array('Pessoa.id' => 'desc'), 'recursive' => -1));
				$this->request->data['User']['pessoa_id']=$ultimaPessoa['Pessoa']['id'];
				$this->User->save($this->request->data);

				$this->Session->setFlash(__('The funcionario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The funcionario could not be saved. Please, try again.'));
			}
		}
		$pessoas = $this->Funcionario->Pessoa->find('list');
		$this->set(compact('pessoas'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Funcionario->exists($id)) {
			throw new NotFoundException(__('Invalid funcionario'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Funcionario->save($this->request->data)) {
				$this->Session->setFlash(__('The funcionario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The funcionario could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Funcionario.' . $this->Funcionario->primaryKey => $id));
			$this->request->data = $this->Funcionario->find('first', $options);
		}
		$pessoas = $this->Funcionario->Pessoa->find('list');
		$this->set(compact('pessoas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Funcionario->id = $id;
		if (!$this->Funcionario->exists()) {
			throw new NotFoundException(__('Invalid funcionario'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Funcionario->delete()) {
			$this->Session->setFlash(__('The funcionario has been deleted.'));
		} else {
			$this->Session->setFlash(__('The funcionario could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
