<?php
/**
 * @package    Joomla! Volunteers
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * View class for a list of roles.
 */
class VolunteersViewRoles extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		VolunteersHelper::addSubmenu('roles');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/volunteers.php';

		$state = $this->get('State');
		$canDo = JHelperContent::getActions('com_volunteers');
		$user  = JFactory::getUser();

		// Set toolbar title
		JToolbarHelper::title(JText::_('COM_VOLUNTEERS') . ': ' . JText::_('COM_VOLUNTEERS_TITLE_ROLES'), 'joomla');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('role.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('role.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('roles.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('roles.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('roles.archive');
			JToolbarHelper::checkin('roles.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'roles.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('roles.trash');
		}

		if ($user->authorise('core.admin', 'com_volunteers') || $user->authorise('core.options', 'com_volunteers'))
		{
			JToolbarHelper::preferences('com_volunteers');
		}
	}
}
