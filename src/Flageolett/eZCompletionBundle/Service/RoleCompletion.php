<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 27.11.14
 */

namespace Flageolett\ezcompletionbundle\Service;

use Flageolett\ezcompletionbundle\Interfaces\CompletionInterface;
use eZ\Publish\Core\Repository\Repository;

class RoleCompletion implements CompletionInterface
{
    /** @var Repository */
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getCompletions()
    {
        $roles = $this->repository->sudo(function()
        {
            $roleList = $this->repository->getRoleService()->loadRoles();
            $roles = array();
            foreach ($roleList as $role) {
                $roles[] = array(
                    'id' => (int)$role->id,
                    'identifier' => $role->identifier,
                );
            }

            return $roles;
        });

        return compact('roles');
    }
}
