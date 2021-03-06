<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

function p1606module_exceptions()
{
	$modules_controllers = Dispatcher::getModuleControllers();
	$core_controllers = array_keys(Dispatcher::getControllers(_PS_CONTROLLER_DIR_));
	
	$hook_module_exceptions = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'hook_module_exceptions`');
	$sql_insert = 'INSERT INTO `'._DB_PREFIX_.'hook_module_exceptions` (`id_hook_module_exceptions`, `id_shop`, `id_module`, `id_hook`, `file_name`) VALUES ';
	$sql_delete = 'DELETE FROM `'._DB_PREFIX_.'hook_module_exceptions` WHERE ';
	
	foreach ($hook_module_exceptions as $exception)
	{
		foreach ($modules_controllers as $module => $controllers)
		{
			if (in_array($exception['file_name'], $controllers) && !in_array($exception['file_name'], $core_controllers))
			{
				$sql_delete .= ' `id_hook_module_exceptions` = '.(int)$exception['id_hook_module_exceptions'].' AND';
				foreach ($controllers as $cont)
				{
					if ($exception['file_name'] == $cont)
						$sql_insert .= '(null, '.(int)$exception['id_shop'].', '.(int)$exception['id_module'].', '.(int)$exception['id_hook'].', \'module-'.pSQL($module).'-'.pSQL($exception['file_name']).'\'),';
				}
			}
		}
	}
	Db::getInstance()->execute($sql_insert);
	Db::getInstance()->execute($sql_delete);
}