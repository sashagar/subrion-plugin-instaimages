<?php

class iaBackendController extends iaAbstractControllerPluginBackend
{
    protected $_name = 'instagram_users_list';

    protected $_table = 'instagram_users_list';

    protected function _indexPage(&$iaView)
    {
        $iaView->grid('_IA_URL_plugins/' . $this->getPluginName() . '/js/admin/index');
    }

    protected function _gridQuery($columns, $where, $order, $start, $limit)
    {
        $sql =
            'SELECT SQL_CALC_FOUND_ROWS '.
            'g.`id`, g.`user_name`, g.`urls`, 1 `update`, 1 `delete` '.
            'FROM `:prefix:table_gmap` g '.
            'LIMIT :start, :limit';

        $sql = iaDb::printf($sql, array(
            'prefix' => $this->_iaDb->prefix,
            'table_instagram_users_list' => $this->getTable(),
            'start' => $start,
            'limit' => $limit
        ));

        return $this->_iaDb->getAll($sql);
    }

    protected function _preSaveEntry(array &$entry, array $data, $action)
    {
        parent::_preSaveEntry($entry, $data, $action);

        iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

        if (!utf8_is_valid($entry['user_name']))
        {
            $entry['user_name'] = utf8_bad_replace($entry['user_name']);
        }
        if (empty($entry['user_name']))
        {
            $this->addMessage('title_is_empty');
        }

        if ($this->getMessages())
        {
            return false;
        }

        return true;
    }

    protected function _setDefaultValues(array &$entry)
    {
        $entry['lang'] = $this->_iaCore->iaView->language;
        $entry['user_name'] = '';
        $entry['urls'] = '';
    }
/**/
}
