<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;

class AdminIdeaInputFilter extends IdeaInputFilter
{
    /** @var AdapterInterface */
    private $dbAdapter;

    public function __construct(
        AdapterInterface $dbAdapter
    ) {
        parent::__construct($dbAdapter);

        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name'        => 'workflowState',
            'allow_empty' => true,
        ]);

        $this->add([
            'name'        => 'workflowStateExtra',
            'allow_empty' => true,
        ]);
    }
}
