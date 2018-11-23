<?php
namespace Api\Service;

use Zend\Permissions\Acl\Acl;

class AclPermissioner
{
    const CACHE_KEY_ACL = 'acl-permission';
    /**
    * @var Acl  
    */
    private $Acl;
    private $resources;
    private $permission;
    private $RoleManager;
    
    public function __construct(
        $resources, 
        $permission,
        RoleManager $RoleManager
        )
    {
        $this->resources = $resources;
        $this->permission= $permission;
        $this->RoleManager=$RoleManager;
    }
    
    /**
    * 
    * @return Acl       
    */
    public function getAclObject()
    {
        $this->Acl = new Acl();
        //add resources
        $this->addResources();
        //add roles
        $this->addRoles();
        //config resoureces and roles
        $this->allow();
        return $this->Acl;
    }
    
    private function addResources()
    {
        $Acl = $this->Acl;
        $resources = $this->resources;
        foreach ($resources as $controller => $facoty)
        {
            $resource = new \Zend\Permissions\Acl\Resource\GenericResource($controller);
            $Acl->addResource($resource);
        }
    }
    
    private function addRoles()
    {
        $Acl = $this->Acl;
        $roles = $this->getRoles();
        foreach ($roles as $roleName)
        {
            $role = new \Zend\Permissions\Acl\Role\GenericRole($roleName);
            $Acl->addRole($role);
        }
    }
    private function getRoles()
    {
        return $this->RoleManager->getRoles();
    }
    private function allow()
    {
        $Acl = $this->Acl;
        $permission = $this->permission;
        foreach ($permission as $resource=>$allowAndDeny)
        {
            $allowRoles = $allowAndDeny['allow'];
            $Acl->allow($allowRoles, $resource);
        }
    }
}
