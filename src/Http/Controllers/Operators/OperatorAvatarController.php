<?php

namespace IlBronza\Operators\Http\Controllers\Operators;

class OperatorAvatarController extends OperatorCRUD
{
    public $allowedMethods = ['avatarFetcher'];

    public function avatarFetcher(string $operator)
    {
        $operator = $this->findModel($operator);

		$url = $operator->getAvatarImageUrl();

        return view('operators::operator._avatar', ['image' => $url]);
    }
}
