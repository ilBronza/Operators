<?php

namespace IlBronza\Operators\Helpers\Companies;

use IlBronza\Ukn\Ukn;

use function session;

class CompanySessionHelper
{
	static function exitFromCompany()
	{
		if ($clientModel = session('clientModel', false))
			Ukn::w('Hai abbandonato la pagina di ' . $clientModel->getFullNameForLogo());

		session()->forget('clientModel');
	}

}