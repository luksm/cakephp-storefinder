<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Http\Client;
use App\Controller\AppController;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 */
class StoresController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $stores = $this->paginate($this->Stores);

        $this->set(compact('stores'));
        $this->set('_serialize', ['stores']);
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);

        $this->set('store', $store);
        $this->set('_serialize', ['store']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('store'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('store'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $store = $this->Stores->get($id);
        if ($this->Stores->delete($store)) {
            $this->Flash->success(__('The store has been deleted.'));
        } else {
            $this->Flash->error(__('The store could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * distancematrix method
     *
     * @param string|null $geolotion current user location
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function distancematrix($geolocation = false)
    {
        $stores = $this->Stores->find('all');
        foreach ($stores as $store) {
            $destinations[] = $store->address;
        }

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
        $options = [];

        $options['origins'] = $geolocation ? $geolocation : 'São Paulo, SP, Brasil';
        $options['destinations'] = implode('|', $destinations);
        $options['mode'] = 'driving';
        $options['language'] = 'pt-BR';
        $options['sensor'] = 'false';
        $options['key'] = Configure::read('Gmaps.API');

        // $resp = $this->curl_get($url, $options);
        $http = new Client();
        $resp = $http->get($url, $options);

        // debug($resp->json['rows'][0]['elements']);
        $destinations = $stores->toArray();
        foreach ($resp->json['rows'][0]['elements'] as $key => $distances) {
            $destinations[$key]->distance = $distances['distance'];
            $destinations[$key]->duration = $distances['duration'];
        }

        for ( $i = 0; $i < count($destinations); $i++){
            for ($j = 0; $j < count($destinations); $j++){
                if($destinations[$i]->distance['value'] < $destinations[$j]->distance['value']){
                    $nowData = $destinations[$i];
                    $destinations[$i] = $destinations[$j];
                    $destinations[$j] = $nowData;
                }
            }
        }

        $this->set('stores', $destinations);
    }

}
