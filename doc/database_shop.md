# products, skus
## 对应关系
products中的一条记录对应了skus表中的多条记录，eg."某品牌巧克力"为一种商品，其中重量分为250g，500g两种，包装形式分为普通包装，精品包装两种；那么这种商品可以组合处4种sku，1) 250g 普通包装， 2) 250g 精品包装， 3) 500g 普通包装， 4) 500g 精品包装；

# shop_categories, shop_attributes, shop_attribute_values
## 对应关系

### shop_categories
商品分类利用父子关系自关联

### shop_attributes, shop_attribute_values
商品属性表记录了一组商品共有的商品属性, 组别关联到商品分类表
商品属性值表记录了对应商品属性的具体值
属性表和属性值表为1对n
eg. 为巧克力分类定义一组属性和对应的属性值:

attribute    |    attribute_values
品牌         |    费列罗
包装形式      |    精装
            |     普通包装
重量         |    250g
            |     500g

属性表属性分为3种，
   1)为基础属性
     比如品牌等
   2)为销售属性
     销售属性由一组最小销售规格构成, 销售的排列组合确定了一种商品的唯一sku
   3)可选其他属性
     作为一种补充性质存在，比如商品的个性化建议等
	 
# sku_attributes
记录了所有sku与其具体属性值的关联关系

## 与skus关系
一条sku记录对应n条sku_attributes 记录,
其中属性关联关系表 is_sell的字段表示销售属性，和shop_attributes冗余
同一个product由is_sell为1的记录可以组合出所有的sku
eg. 250g 普通包装的费列罗巧克力，在sku表中有一条记录，
并且在sku_attributes表中有3条记录，
1) 关联品牌属性 2)关联销售属性重量，属性值为250g 3)关联销售属性包装，属性值为普通包装

