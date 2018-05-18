<?php
/**
 * AppModel
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Translation\FileLoader as FileLoader;
use Illuminate\Filesystem\Filesystem as Filesystem;
use Illuminate\Translation\Translator;

class AppModel extends \Illuminate\Database\Eloquent\Model
{
    public function validate($data)
    {
        $translation_file_loader = new FileLoader(new Filesystem, __DIR__ . '../lang');
        $translator = new Translator($translation_file_loader, 'en');
        $factory = new ValidatorFactory($translator);
        $v = $factory->make($data, $this->rules);
        $v->passes();
        return $v->failed();
    }
    public function scopeFilter($query, $params = array())
    {
        $sortby = (!empty($params['sortby'])) ? $params['sortby'] : 'desc';
        if (!empty($params['fields'])) {
            $fields = explode(',', $params['fields']);
            $query->select($fields);
        }
        if (empty($query->getQuery()->joins)) {
            if (!empty($params['sort'])) {
                $query->orderBy($params['sort'], $sortby);
            } else {
                if (!empty($params['filter']) && $params['filter'] == 'popular' && $query->getQuery()->from == 'search_keywords') {
                    $query->orderBy('search_log_count', 'desc');
                } else {
                    $query->orderBy('id', $sortby);
                }
            }
        }
        if (!empty($query->getQuery()->joins)) {
            if (!empty($params['sort'])) {
                if (strpos($params['sort'], '.')) {
                    $query->orderBy(str_replace('.', '_', $params['sort']), $sortby);
                } else {
                    $query->orderBy($query->getQuery()->from . '.' . $params['sort'], $sortby);
                }
            } else {
                $query->orderBy($query->getQuery()->from . '.id', $sortby);
            }
        }
        if (!empty($params['q']) && $this->qSearchFields) {
            $search_fields = $this->qSearchFields;
            $query->where(function ($q) use ($params, $search_fields) {
            
                foreach ($search_fields as $field) {
                    $search = $params['q'];
                    $q->orWhere($field, 'ilike', "%$search%");
                }
            });
        }
        if (!empty($params['page'])) {
            $offset = ($params['page'] - 1) * PAGE_LIMIT + 1;
            $query->skip($offset)->take(PAGE_LIMIT);
        }
        if (!empty($params['filter'])) {
            if (!empty($params['filter']) && $params['filter'] == 'popular' && $query->getQuery()->from != 'search_keywords') {
                $query->where($query->getQuery()->from . '.is_popular', true);
            }
            if ($params['filter'] == 'active') {
                $query->where($query->getQuery()->from . '.is_active', 'true');
            }
            if ($params['filter'] == 'inactive') {
                $query->where($query->getQuery()->from . '.is_active', false);
            }
            if ($params['filter'] == 'all') {
                $query->whereIn($query->getQuery()->from . '.is_active', array(
                    false,
                    true
                ));
            }
        }
        if (!empty($params['is_active'])) {
            $query->where('is_active', $params['is_active']);
        }
        return $query;
    }
}
