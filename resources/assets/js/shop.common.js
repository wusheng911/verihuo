/****************************************
 * View Product Page
 * 
 ****************************************/


/*****************
 * 获取选中属性的货号
 * 
 * @param {String} option 在选中的属性的货号之间取并集(union)还是交集(intersect)
 * 
 * @returns array 
 *****************/
function getSelectedSkuIds(option) 
{
    var sku_ids = new Array();
    
    $(".selected").each(function() {
        local_skuids = $(this).data("skuids").toString().split(",");
        
        if (sku_ids.length == 0) {
            sku_ids = local_skuids;
        } else {
            if (option.toLowerCase() == 'union') {
                sku_ids = Array.union(sku_ids, local_skuids);
            } else {
                sku_ids = Array.intersect(sku_ids, local_skuids);
            }
        }
    });
    
    return sku_ids;
}

function getSkuById(sku_id, skus) 
{
    sku = new Object();
    
    for(i=0; i < skus.length; i++) {
        if (skus[i].id == sku_id) {
            sku = skus[i];
        }
    }
    return sku;
}

function getPricesBySkuIds(sku_ids, skus) 
{
    prices = new Array();
    
    for(i=0; i<sku_ids.length; i++) {
        sku = getSkuById(sku_ids[i], skus);
        prices.push(sku.price);
    }
    prices.sort();
    return prices;
}


/***********************************
 * 输出商品详情的HTML
 * 
 * @param {Object} display_attrs 由getDisplayAttributes()函数获得的属性内容HashTable
 * @returns {String} HTML字符串
 */
function renderProductAttributes(display_attrs) {
    var result = "";
    var index = 0;
    
    for (var i in display_attrs) {
        index++;
    }
    
    if (index > 0) {
        result = '<ul class="no-bullet">';
        for(var k in display_attrs) {
            display_attr = display_attrs[k];
            result += '<li title="' + display_attr.show_name + '">';
            result += display_attr.show_name + "：";
            result += display_attr.value.join("，");
            result += '</li>';
        }        
        result += '</ul>';
    }
    
    return result;
}

/****************************************
 * 获得完整的商品详情属性内容
 * 
 * @param {Object} shop_attrs 由API获得的商品属性列表
 * @param {Array} sku_ids 当前选中的属性列表
 * @returns {display_attrs|Object} 商品详情属性内容的HashTable
 */
function getDisplayAttributes(shop_attrs, sku_ids) {
    
    display_attrs = new Object();
    
    //为了防止子元素和父元素的顺序颠倒, 这里分为两步, 先处理所有的父元素和独立元素, 再处理所有的子元素
    for(var i in shop_attrs) {
        attr = shop_attrs[i];
        
        if ((sku_ids.length > 0) && (sku_ids.indexOf("0") == -1)) {
            sku_ids.push("0");
        }
        
        for(var k in attr.shop_attribute_values) {
            attr_value = attr.shop_attribute_values[k];
            
            matched_skus = Array.intersect(attr_value.sku_ids, sku_ids);
            
            //如果在选中的属性的sku中，才显示这个属性
            if (matched_skus.length > 0 || sku_ids.length == 0) {
                //如果是根(父亲)属性
                if (typeof(attr.parent_list) == "undefined" || attr.parent_list == "0") {
                    if (typeof(display_attrs[attr.shop_attribute_id]) == "undefined") {                  
                        display_attrs[attr.shop_attribute_id] = new Object();               
                    }
                    
                    if (typeof(display_attrs[attr.shop_attribute_id].value) == "undefined") {
                        display_attrs[attr.shop_attribute_id].value = new Array();
                    }
                    
                    display_attrs[attr.shop_attribute_id].show_name = attr.shop_attribute_showname; 
                    display_attrs[attr.shop_attribute_id].value.push(attr_value.value);
                }
            }
        }
    }
    
    //处理所有的子元素
    for(var i in shop_attrs) {
        attr = shop_attrs[i];
                
        for(var k in attr.shop_attribute_values) {
            attr_value = attr.shop_attribute_values[k];
            
            matched_skus = Array.intersect(attr_value.sku_ids, sku_ids);
            
            //如果在选中的属性的sku中，才显示这个属性
            if (matched_skus.length > 0 || sku_ids.length == 0) {
                //如果是儿子属性, 如系列等, 则显示在父属性中
                if ((typeof(attr.parent_list) != "undefined") && (attr.parent_list != "0")) {
                    parent_ids = attr.parent_list.toString().split(",");
                    root_id = parent_ids.shift();
                    
                    if (root_id.length > 0) {
                        //因为在第一步中已经处理了所有的父亲属性, 所以这里的对象一定有值
                        display_attrs[root_id].value.push(attr_value.value);
                    }                  
                }
            }
        }
    }  
    
    return display_attrs;   
}

