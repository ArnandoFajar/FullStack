<?php

namespace App\Controllers;

use App\Models\ProductsModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class Products extends ResourceController
{
    protected $productsModel;
    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        helper(['form']);
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        $data = $this->productsModel->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->productsModel->find($id);
        if (!$data) {
            return $this->failNotFound('No Data Found');
        }
        return $this->respond($data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'title' => 'required',
            'price' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getVar('title'),
            'price' => $this->request->getVar('price')
        ];
        $this->productsModel->save($data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Data Inserted'
            ]
        ];
        return $this->respondCreated($response);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $rules = [
            'title' => 'required',
            'price' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getVar('title'),
            'price' => $this->request->getVar('price')
        ];

        $findById = $this->productsModel->find($id);
        if (!$findById) {
            return $this->failNotFound('No Data Found');
        }
        $this->productsModel->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $findById = $this->productsModel->find($id);
        if (!$findById) {
            return $this->failNotFound('No Data Found');
        }
        $this->productsModel->delete($id);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Deleted'
            ]
        ];
        return $this->respond($response);
    }
}
