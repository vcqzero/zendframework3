<?php
namespace Api\Service;

use Zend\Permissions\Acl\Acl;
use Api\Service\UserManager;
use Zend\Cache\Storage\Adapter\Filesystem;

class AclPermissioner
{
    const CACHE_KEY_ACL = 'acl-permission';
    /**
    * @var Acl  
    */
    private $Acl;
    private $Cache;
    private $resources;
    private $permission;
    
    public function __construct(
        Filesystem $Cache,
        $resources, 
        $permission)
    {
        $this->Cache = $Cache;
        $this->resources = $resources;
        $this->permission= $permission;
    }
    
    /**
    * 
    * @return Acl       
    */
    public function getAcl()
    {
        $Cache = $this->Cache;
        if (!$Cache->hasItem(self::CACHE_KEY_ACL))
        {
            $this->Acl = new Acl();
            //add resources
            $this->addResources();
            //add roles
            $this->addRoles();
            //config resoureces and roles
            $this->allow();
            $this->Cache->setItem(self::CACHE_KEY_ACL, $this->Acl);
        }else {
            $acl_serialize = $Cache->getItem(self::CACHE_KEY_ACL);
            $this->Acl = $acl_serialize;
        }
        
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
        $roles = [
            UserManager::ROLE_GUEST,
            UserManager::ROLE_SUPER_ADMIN,
            UserManager::ROLE_WORKYARD_ADMIN,
            UserManager::ROLE_WORKYARD_GUARD,
        ];
        return $roles;
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
