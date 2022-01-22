<?php


namespace app\common\lib;

class Arr
{
    /**
     * 分类树， 支持无限极分类
     * @param $data
     * @return array
     */
    public static function getTree($data)
    {
        $items = [];
        foreach ($data as $v) {
            $items[$v['id']] = $v;
        }
        $tree = [];
        foreach ($items as $id => $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['child'][] = &$items[$id];
            } else {
                $items[$id]['child'] = [];
                $tree[] = &$items[$id];
            }
        }
        return $tree;
    }
    
    public static function sliceTreeArr($data, $firstCount = 5, $secondCount = 3, $threeCount = 5)
    {
        $data = array_slice($data, 0, $firstCount);
        foreach ($data as $k => $v) {
            if (!empty($v['list'])) {
                $data[$k]['list'] = array_slice($v['list'], 0, $secondCount);
                foreach ($v['list'] as $kk => $vv) {
                    if (!empty($vv['list'])) {
                        $data[$k]['list'][$kk]['list'] = array_slice($vv['list'], 0, $threeCount);
                    }
                }
            }
        }
        
        return $data;
    }
    
    /**
     * 分页默认返回的数据
     * @param $num
     * @return array
     */
    public static function getPaginateDefaultData($num)
    {
        $result = [
            "total" => 0,
            "per_page" => $num,
            "current_page" => 1,
            "last_page" => 0,
            "data" => [],
        ];
        return $result;
    }

    /**
     * 二维数组分组
     * @param $arr
     * @param $key
     * @return array
     */
    public static function groupArr($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys   要排序的键字段
     * @param string $sort  排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    public static function arraySort($array, $keys, $sort = SORT_DESC)
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    public static function uniqueByKey($arr, $key)
    {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {   //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
            } else {
                $tmp_arr[$k] = $v[$key];  //将不同的值放在该数组中保存
            }
        }
        //ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
        return $arr;
    }
}
