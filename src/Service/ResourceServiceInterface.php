<?php


namespace Jiejunf\Resourceful\Service;


interface ResourceServiceInterface
{
    public function index();

    public function model($id);

    public function show($id);

    public function store($inputs);

    public function update($id, $inputs);

    public function destroy($id);
}
