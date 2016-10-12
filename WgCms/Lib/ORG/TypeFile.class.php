<?php
/**
 +------------------------------------------------------------------------------
 * 分类处理类
 +------------------------------------------------------------------------------
 * @category  ORG
 * @package   Org
 * @author    Sly
 +------------------------------------------------------------------------------
 */
class TypeFile {
	//实例化的分类对象
	protected $Type;
	
	/**
	 +----------------------------------------------------------
	 * 架构函数
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 */
	public function __construct($className) {
		$this->Type = M ( $className );
	}
	
	/**
     +----------------------------------------------------------
	 * 列出分类清单
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return $list
     +----------------------------------------------------------
	 */
	public function listType($pid) {
		$Type = $this->Type;
		$type = $Type->where ( 'pid=' . $pid )->order ( 'oid' )->select ();
		$list = $this->fetchType ( $type );
		return $list;
	}
	
	/**
     +----------------------------------------------------------
	 * 列出子分类清单
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param  array $type 分类实例
	 * @param  array $list 分类清单容器
     +----------------------------------------------------------
	 * @return $list
     +----------------------------------------------------------
	 */
	protected function fetchType($type, $list = array()) {
		$Type = $this->Type;
		foreach ( $type as $key => $value ) {
			$value ['pathimg'] = $this->getPathimg ( $value );
			array_push ( $list, $value );
			if ($value ['child'] == 'Y') {
				$subType = $Type->where ( 'pid=' . $value ['id'] )->order ( 'oid' )->select ();
				$list = $this->fetchType ( $subType, $list );
			}
		}
		return $list;
	}
	
	/**
	 +----------------------------------------------------------
	 * 分类层次显示优化
	 +----------------------------------------------------------
	 * @access protected
	 +----------------------------------------------------------
	 * @param  array $type 分类实例
	 +----------------------------------------------------------
	 * @return $pathimg
	 +----------------------------------------------------------
	 */
	protected function getPathimg($type) {
		$depth = $type ['depth'];
		if ($depth) {
			for($i = 0; $i < $depth; $i ++) {
				if ($i == $depth - 1) {
					$pathimg .= "<img src='__TMPL__/Public/images/tree_line1.gif' width='17' height='16'>";
				} else {
					$pathimg .= "<img src='__TMPL__/Public/images/tree_line3.gif' width='17' height='16'>";
				}
			}
		}
		if ($type ['child'] == 'Y') {
			$pathimg .= "<img src='__TMPL__/Public/images/tree_folder4.gif' width='15' height='15'>";
		} else {
			$pathimg .= "<img src='__TMPL__/Public/images/tree_folder3.gif' width='15' height='15'>";
		}
		return $pathimg;
	}
	
	/**
	 +----------------------------------------------------------
	 * 添加新的分类
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $pid  新分类的父ID
	 * @param  string $name 新分类名称
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function addType($pid, $name) {
		$Type = $this->Type;
		$type ['name'] = $name;
		$type ['pid'] = $pid;
		$type ['child'] = 'N';
		$maxOid = $Type->where ( 'pid=' . $pid )->field ( 'max(oid) as value' )->find ();
		$type ['oid'] = $maxOid ['value'] + 1;
		$ptype = $Type->where ( 'id=' . $pid )->find (); //父分类实例
		$type ['depth'] = $ptype ['depth'] + 1;
		if ($pid == 1 || $pid == 2 || $pid == 29) {
			$type ['depth'] = 0;
		}
		$result = $Type->add ( $type );
		if ($result) {
			$Type->where ( 'id=' . $pid )->setField ( 'child', 'Y' ); //设置父分类为有子分类状态
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 修改分类
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $id   需要修改的分类ID
	 * @param  string $name 分类名称
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function editType($id, $name) {
		$Type = $this->Type;
		$result = $Type->where ( 'id=' . $id )->setField ( 'name', $name );
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 删除分类
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $id  需要删除的分类ID
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function delType($id) {
		$Type = $this->Type;
		$type = $Type->where ( 'id=' . $id )->field ( 'pid,child' )->find ();
		$pid = $type ['pid'];
		$child = $type ['child'];
		$result = $Type->where ( 'id=' . $id )->delete ();
		if ($result) {
			if ($child == 'Y') {
				if (! ($Type->where ( 'pid=' . $id )->delete ())) {
					return false;
				}
			}
			if (! ($Type->where ( 'pid=' . $pid )->select ())) {
				$Type->where ( 'id=' . $pid )->setField ( 'child', 'N' );
			}
		} else {
			return false;
		}
		return true;
	}
	
	/**
	 +----------------------------------------------------------
	 * 上移分类
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $id  需要上移的分类ID
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function sortUp($id) {
		$Type = $this->Type;
		$type = $Type->where ( 'id=' . $id )->field ( 'pid,oid' )->find ();
		$pid = $type ['pid'];
		$oid = $type ['oid'];
		$condition ['pid'] = $pid;
		$condition ['oid'] = array ('lt', $oid );
		$type = $Type->where ( $condition )->order ( 'oid desc' )->field ( 'id,oid' )->find ();
		if ($type) {
			$Type->where ( 'id=' . $type ['id'] )->setField ( 'oid', $oid );
			$Type->where ( 'id=' . $id )->setField ( 'oid', $type ['oid'] );
		} else {
			return false;
		}
		return true;
	}
	
	/**
	 +----------------------------------------------------------
	 * 下移分类
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $id  需要下移的分类ID
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function sortDown($id) {
		$Type = $this->Type;
		$type = $Type->where ( 'id=' . $id )->field ( 'pid,oid' )->find ();
		$pid = $type ['pid'];
		$oid = $type ['oid'];
		$condition ['pid'] = $pid;
		$condition ['oid'] = array ('gt', $oid );
		$type = $Type->where ( $condition )->order ( 'oid asc' )->field ( 'id,oid' )->find ();
		if ($type) {
			$Type->where ( 'id=' . $type ['id'] )->setField ( 'oid', $oid );
			$Type->where ( 'id=' . $id )->setField ( 'oid', $type ['oid'] );
		} else {
			return false;
		}
		return true;
	}
	
	/**
	 +----------------------------------------------------------
	 * 输出分类路径(数组形式)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $id  需要获取路径的分类ID
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getPath($id) {
		$typeNumArr = array ();
		$Type = $this->Type;
		array_push ( $typeNumArr, $id );
		do {
			$type = $Type->where ( 'id=' . $id )->field ( 'pid' )->find ();
			$id = $type ['pid'];
			array_push ( $typeNumArr, $id );
		} while ( $id != 0 );
		sort ( $typeNumArr );
		return $typeNumArr;
	}
	
	/**
	 +----------------------------------------------------------
	 * 输出分类路径名称(数组形式)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int $id  需要获取路径的分类ID
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getPathName($id) {
		$typeNameArr = array ();
		$Type = $this->Type;
		$typeNumArr = $this->getPath ( $id );
		foreach ( $typeNumArr as $key => $value ) {
			if ($value == 0)
				continue;
			$type = $Type->where ( 'id=' . $value )->field ( 'name' )->find ();
			array_push ( $typeNameArr, $type ['name'] );
		}
		return $typeNameArr;
	}
}
?>