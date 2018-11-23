<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\UserManager;
use Api\Service\RoleManager;

class MenuHelper extends AbstractHelper
{

    public function getMenu($role)
    {
        $all_section_menus = $this->getAllSectionMenus();
        $all_section_menus = $this->filter($all_section_menus, $role);
        return $all_section_menus;
    }

    private function filter($all_section_menus, $role)
    {
        // 首先判断用户角色是否允许
        foreach ($all_section_menus as $section_key => $section) {
            $menus = $section['menus'];
            foreach ($menus as $menus_key => $menu) {
                $submenus = $menu['submenus'];
                foreach ($submenus as $submenus_key => $submenu) {
                    $allow = $submenu['allow'];
                    if (empty($allow)) {
                        continue;
                    }
                    if (in_array($role, $allow)) {
                        continue;
                    }
                    unset($all_section_menus[$section_key][$menus_key]['submenus'][$submenus_key]);
                }
            }
        }
        
        // 然后将不含子菜单的删除
        foreach ($all_section_menus as $section_key => $section) {
            $menus = $section['menus'];
            foreach ($menus as $key2 => $menu) {
                $submenus = $menu['submenus'];
                if (empty($submenus)) {
                    unset($navbars[$section_key][$key2]);
                }
            }
        }
        
        // 然后将不含子菜单的删除
        foreach ($all_section_menus as $section_key => $section) {
            $menus = $section['menus'];
            if (empty($menus)) {
                unset($navbars[$section_key]);
            }
        }
        
        return $all_section_menus;
    }

    private function getAllSectionMenus()
    {
        return [
            '操作台部分' => [
//                 'section-title' => '首页',
                'menus' => [
                    'menu-1' =>  [
                        'icon'  => 'icon-home',
                        'title' =>'首页',
                        'submenus' => [
                            'submenu-1'=>[
                                'icon' => '',
                                'href' => '/',
                                'title' => '首页',
                                'allow' => [
                                    RoleManager::ROLE_GUEST
                                ],
                            ],
                        ],//end submenus
                    ],
                ],//end menus
            ],
            
            '系统设置部分' => [
                'section-title' => '用户',
                'menus' => [
                    'menu-1' =>  [
                        'icon'  => 'fa fa-users',
                        'title' =>'用户管理',
                        'submenus' => [
                            'submenu-1'=>[
                                'icon' => '',
                                'href' => '/user',
                                'title' => '用户管理',
                                'allow' => [
                                    RoleManager::ROLE_GUEST
                                ],
                            ],
                        ],//end submenus
                    ],
                ],//end menus
            ],
            
            '站点设置部分' => [
                'section-title' => '系统设置',
                'menus' => [
                    'menu-1' =>  [
                        'icon'  => 'icon-settings',
                        'title' =>'站点设置',
                        'submenus' => [
                            'submenu-1'=>[
                                'icon' => '',
                                'href' => '/website',
                                'title' => '站点设置',
                                'allow' => [
                                    RoleManager::ROLE_GUEST,
                                    RoleManager::ROLE_SUPER_USER,
                                ],
                            ],
                        ],//end submenus
                    ],
                ],//end menus
            ],
            
            '账户中心' => [
                'section-title' => '账户中心',
                'menus' => [
                    'menu-1' =>  [
                        'icon'  => 'fa fa-user',
                        'title' =>'我的账户',
                        'submenus' => [
                            'submenu-1'=>[
                                'icon' => '',
                                'href' => '/account',
                                'title' => '个人中心',
                                'allow' => [
                                    RoleManager::ROLE_SUPER_USER,
                                    RoleManager::ROLE_GUEST
                                ],
                            ],
                            
                        ],//end submenus
                    ],
                ],//end menus
            ],
        ];
    }
}
