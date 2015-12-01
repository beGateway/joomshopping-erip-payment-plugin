<?php
defined('_JEXEC') or die('Restricted access');
/**
 * Install Script file of joomshoppingerip component
*/

class PlgsystemjoomshoppingeripInstallerScript
	/**
	 * method to install the component
	 *
	 * @return void
	 */

	function install($parent){
		//$query	= $db->getQuery(true);
		//$parent->getParent()->setRedirectURL('index.php?option=com_jslearn');
	}

	/**
	 *
	 * @return void
	 */

	function uninstall($parent){
		//$query	= $db->getQuery(true);

		if (JFolder::exists(JPATH_ROOT.'/components/com_jshopping/payments/pm_erip')) {

		}
		$db 	= JFactory::getDBO();

		$query->clear();
		$query->delete($db->quoteName('#__jshopping_payment_method'))
		$db->setQuery($query);

		try
			$db->execute();
		}
		catch (RuntimeException $e)
		{

		}

	/**
	 *
	 * @return void
	 */

	function update($parent)
		// $parent is the class calling this method
		//echo '<p>' . JText::_('COM_JSLEARN_UPDATE_TEXT') . '</p>';
	}

	/**
	 *
	 * @return void
	 */

	function preflight($type, $parent)
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		if ($type == 'install' || $type == 'update') {
			if (!JFolder::exists(JPATH_ROOT.'/components/com_jshopping')) {
				throw new RuntimeException('JoomShopping component not found!');
			}
		}
	}

	/**
	 *
	 * @return void
	 */

	function postflight($type, $parent)
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		if ($type == 'install' || $type == 'update') {
			if (JFolder::exists(JPATH_ROOT.'/components/com_jshopping/payments/pm_erip')) {
				JFolder::delete(JPATH_ROOT.'/components/com_jshopping/payments/pm_erip');
			}
			$move_folder = JFolder::move(JPATH_ROOT.'/plugins/system/joomshoppingerip/libraries/pm_erip',JPATH_ROOT.'/components/com_jshopping/payments/pm_erip');
			if ($move_folder === true) {

				$db 	= JFactory::getDBO();

				$query->clear();
				$query->select($db->quoteName('p.payment_id'))
					->where($db->quoteName('p.scriptname') . ' = "pm_erip"');

				$db->setQuery($query);
				$existing_connection = $db->loadResult();
				if (!$existing_connection) {
					$query->clear();
						->columns(
							array(
								$db->quoteName('payment_id'),
								$db->quoteName('payment_code'),
								$db->quoteName('payment_class'),
								$db->quoteName('scriptname'),
								$db->quoteName('payment_publish'),
								$db->quoteName('payment_ordering'),
								$db->quoteName('payment_params'),
								$db->quoteName('payment_type'),
								$db->quoteName('price'),
								$db->quoteName('price_type'),
								$db->quoteName('tax_id'),
								$db->quoteName('image'),
								$db->quoteName('show_descr_in_email'),
								$db->quoteName('show_bank_in_order'),
								$db->quoteName('order_description'),
								$db->quoteName('name_en-GB'),
								$db->quoteName('description_en-GB'),
								$db->quoteName('name_de-DE'),
								$db->quoteName('description_de-DE')
							)
						)
						->values(
								"''" . "," .
								"'erip'" . "," .
								"'pm_erip'" . "," .
								"'pm_erip'" . "," .
								"1" . "," .
								"1" . "," .
								"'shop_id=\nshop_key=\napi_url=\nservice_no=\nagent=\naccount_no=\npayment_method_description=\ncompany_name=\ntree_path_email=\nreceipt_text=\nservice_text=\n'" . "," .
								"2" . "," .
								"'0.00'" . "," .
								"0" . "," .
								"1" . "," .
								"''" . "," .
								"0" . "," .
								"1" . "," .
								"''" . "," .
								"'Erip'" . "," .
								"''" . "," .
								"'Erip'" . "," .
								"''"
							);

					$db->setQuery($query);
					try
						$db->execute();
					}
					catch (RuntimeException $e)
					{
						throw new RuntimeException('Erip payment option can not be created!<br/>'.$e->getMessage());
					}
        }

				$query->clear();
					->from($db->quoteName('#__jshopping_order_status') . ' AS os')
					->where($db->quoteName('os.status_code') . ' = "A"');

				$db->setQuery($query);

				$existing_order_status = $db->loadResult();
				if (!$existing_order_status) {
					$query->clear();
						->columns(
							array(
								$db->quoteName('status_id'),
								$db->quoteName('status_code'),
								$db->quoteName('name_en-GB'),
								$db->quoteName('name_de-DE')
							)
						)
						->values(
								"''" . "," .
								"'A'" . "," .
								"'[ERIP] Awaiting payment'" . "," .
								"'[ERIP] Awaiting payment'"
  					);

					$db->setQuery($query);
					try
						$db->execute();
					}
					catch (RuntimeException $e)
					{
						throw new RuntimeException('Erip order status can not be created!<br/>'.$e->getMessage());
					}
				}

				throw new RuntimeException('Erip payment option can not be created!<br/>'.$move_folder);
			}
		}
	}
}