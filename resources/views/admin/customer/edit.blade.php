@extends('layouts.admin')

@section('title','学生管理')


@push('stylesheets')

<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/brand/brand_create.js') }}"></script>


<link rel="stylesheet" type="text/css" href="/assets/libs/uploadifive/Huploadify.css"/>

<script type="text/javascript" src="/assets/libs/uploadifive/jquery.Huploadify.js"></script>
<style type="text/css">
</style>
<script type="text/javascript">
    $(function(){
        $('#upload').Huploadify({
            auto:true,
            fileTypeExts:'*.jpg;*.png;*.gif',
            multi:true,
            formData:{key:123456,key2:'vvvv',_token:"{{csrf_token()}}"},
            fileSizeLimit:9999,
            showUploadedPercent:true,//是否实时显示上传的百分比，如20%
            showUploadedSize:true,
            removeTimeout:9999999,
            uploader:"{{url('admin/upimgcustomer')}}",
            onUploadStart:function(){

            },
            onInit:function(){

            },
            'onUploadSuccess' : function(file, data, response) {

                $('input[name="customer[portrait]"]').val(data);
                $('#thumb').attr('src',data);
                alert('图片上传成功');
            },
            onDelete:function(file){
                console.log('删除的文件：'+file);
                console.log(file);
            }
        });
    });

function isEmpty(){
    var x = document.getElementById("portrait").value;

if(trim(x)==""){
alert("portrait不能为空!");
return false;
}
}
</script>


<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css') }}">
<script src="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js') }}"></script>

<script type="text/javascript">
    $('#datetimepicker').datetimepicker({
        format: 'Y-m-d'
    });
</script>



@endpush

@section('content')

    @if(empty($customer))
        {!! Form::open(['url'=>action('Admin\CustomerController@store'),'method'=>'post', 'name'=>'form1']) !!}

        {!! Form::close() !!}
    @else
        {!! Form::open(['url'=>action('Admin\CustomerController@update',['id'=>$customer->id]),'method'=>'put']) !!}
        <div class="form-group">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">




                        <div class="row form-group">
                            <label class="col-lg-2 control-label">学生名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer[user_name]" placeholder="请输入" value="{{$customer->user_name}}" >
                        </div>
                        <br>




                        <div class="row form-group">
                            <label class="col-lg-2 control-label">学生电话</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer[phone]" placeholder="请输入" value="{{$customer->phone}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">学生邮件</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer[email]" placeholder="请输入" value="{{$customer->email}}" >
                        </div>
                        <br>






                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">真实姓名</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer[real_name]" placeholder="请输入" value="{{$customer->real_name}}" >
                        </div>
                        <br>



                        <div class="row form-group">
                            <label class="col-lg-2 control-label">性别</label>

                            <select class="col-lg-3 form-control-static" name="customer[sex]">
                                <option value ="1" {!! ($customer->sex==1) ? "selected = \"selected\"":"" !!}>男</option>
                                <option value ="0" {!! ($customer->sex==0) ? "selected = \"selected\"":"" !!}  >女</option>



                            </select>
                        </div>
                        <br>

                        <div class="row form-group">
                            <label class="col-lg-2 control-label">生日</label>


                            <input type="text"  id="datetimepicker" class="col-lg-8 form-control-static" name="customer[birthday]"  value="{{$customer->birthday}}">


                        </div>
                        <br>


                        <br>





                        <div class="row form-group">
                            <label class="col-lg-2 control-label">学校名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer[school_name]" placeholder="请输入" value="{{$customer->school_name}}" >
                        </div>
                        <br>
                        @if($customer_info1)
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">状态</label>


                            <select class="col-lg-3 form-control-static" name="customer_info1[status]">
                                <option value ="1" {!! ($customer_info1->status==1) ? "selected = \"selected\"":"" !!}>active</option>
                                <option value ="2" {!! ($customer_info1->status==2) ? "selected = \"selected\"":"" !!}  >pending</option>
                                <option value ="3" {!! ($customer_info1->status==3) ?  "selected = \"selected\"":"" !!}>closed</option>


                            </select>
                        </div>
                        <br>


                        <div class="row form-group">
                            <label class="col-lg-2 control-label">课程ID</label>


                            <select class="col-lg-3 form-control-static" name="customer_info1[course_id]">
                                <option value ="1" {!! ($customer_info1->course_id==1) ? "selected = \"selected\"":"" !!}>study abroad</option>
                                <option value ="2" {!! ($customer_info1->course_id==2) ? "selected = \"selected\"":"" !!}  >business english</option>
                                <option value ="3" {!! ($customer_info1->course_id==3) ?  "selected = \"selected\"":"" !!}>kids global</option>
                                <option value ="4"  {!! ($customer_info1->course_id==4) ?  "selected = \"selected\"":"" !!}>primary</option>
                                <option value ="5" {!! ($customer_info1->course_id==5) ?  "selected = \"selected\"":"" !!}>junior hight</option>
                                <option value ="6" {!! ($customer_info1->course_id==6) ?  "selected = \"selected\"":"" !!}>adults english</option>

                            </select>
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">cid学号</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[cid]" placeholder="请输入" value="{{$customer_info1->cid}}" >
                        </div>
                        <br>

                        <div class="row form-group">
                            <label class="col-lg-2 control-label">等级</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[level]" placeholder="请输入" value="{{$customer_info1->level}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">考试成绩</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[test_score]" placeholder="请输入" value="{{$customer_info1->test_score}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">家长姓名</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[parents_name]" placeholder="请输入" value="{{$customer_info1->parents_name}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">家长电话</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[parents_phone]" placeholder="请输入" value="{{$customer_info1->parents_phone}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">绩点</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[points]" placeholder="请输入" value="{{$customer_info1->points}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">协议</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[contract]" placeholder="请输入" value="{{$customer_info1->contract}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">建议</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[advisor]" placeholder="请输入" value="{{$customer_info1->advisor}}" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">获奖经历</label>
                            <input type="text" class="col-lg-8 form-control-static" name="customer_info1[awards]" placeholder="请输入" value="{{$customer_info1->awards}}" >
                        </div>
                        <br>
                         @else

                            <div class="row form-group">
                                <label class="col-lg-2 control-label">状态</label>


                                <select class="col-lg-3 form-control-static" name="customer_info1[status]">
                                    <option value ="1" >active</option>
                                    <option value ="2"  >pending</option>
                                    <option value ="3" >closed</option>


                                </select>
                            </div>
                            <br>


                            <div class="row form-group">
                                <label class="col-lg-2 control-label">课程ID</label>


                                <select class="col-lg-3 form-control-static" name="customer_info1[course_id]">
                                    <option value ="1" >study abroad</option>
                                    <option value ="2" >business english</option>
                                    <option value ="3" >kids global</option>
                                    <option value ="4"  >primary</option>
                                    <option value ="5" >junior hight</option>
                                    <option value ="6" >adults english</option>

                                </select>
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">cid学号</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[cid]" placeholder="请输入" value="" >
                            </div>
                            <br>

                            <div class="row form-group">
                                <label class="col-lg-2 control-label">等级</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[level]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">考试成绩</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[test_score]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">家长姓名</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[parents_name]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">家长电话</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[parents_phone]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">绩点</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[points]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">协议</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[contract]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">建议</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[advisor]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="row form-group">
                                <label class="col-lg-2 control-label">获奖经历</label>
                                <input type="text" class="col-lg-8 form-control-static" name="customer_info1[awards]" placeholder="请输入" value="" >
                            </div>
                            <br>

                        @endif


                        <script>
                            function Scroll(obj, h, s){
                                var h = h || 200;
                                var s = s || 1.2;
                                var obj = typeof(obj)=="string"?document.getElementById(obj):obj;
                                if(obj == undefined){return false;}
                                var status = obj.getAttribute("status")==null;
                                var oh = parseInt(obj.offsetHeight);
                                obj.style.height = oh;
                                obj.style.display = "block";
                                obj.style.overflow = "hidden";
                                if(obj.getAttribute("oldHeight") == null){
                                    obj.setAttribute("oldHeight", oh);
                                }else{
                                    var oldH = Math.ceil(obj.getAttribute("oldHeight"));
                                }
                                var reSet = function(){
                                    if(status){
                                        if(oh < h){
                                            oh = Math.ceil(h-(h-oh)/s);
                                            obj.style.height = oh+"px";
                                        }else{
                                            obj.setAttribute("status",false);
                                            window.clearInterval(IntervalId);
                                        }
                                    }else{
                                        obj.style.height = oldH+"px";
                                        obj.removeAttribute("status");
                                        window.clearInterval(IntervalId);
                                    }
                                }
                                var IntervalId = window.setInterval(reSet,10);
                                return status;
                            }
                            window.onload= function(){
                                var $ = function(id){return document.getElementById(id)};
                                $('menu').onclick = function(){
                                    Scroll('menu',this.scrollHeight,1.2);
                                }
                                $('test').onclick = function(){
                                    Scroll('test',300,1.2);
                                }
                            }
                        </script>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">选择老师</label>
                            <div style="border:1px solid #ccc;padding-left:30px;height:100px;overflow:hidden" class="col-lg-8 control-label"  id="menu">


                                <label class="row">master老师</label> <div style="text-align: right;float:right;">(点击该区域展开或收缩)</div><div style="clear:both"></div>
                                <div class="row">

                                    @if($teachersmaster)
                                @foreach($teachersmaster as $teacher)

                            <input name="teacher_customer[{{$teacher->id}}]" type="checkbox" value="{{$teacher->id}}" {{ empty(in_array($teacher->id, $teacher_customer)) ? "" : "checked" }} />{{$teacher->name}}
                            @endforeach
                                    @endif

                                </div>
                                <br/>
                            <label class="row">普通老师</label>
                             <div class="row">
                                 @if($teachers)
                            @foreach($teachers as $teacher)

                                <input name="teacher_customer[{{$teacher->id}}]" type="checkbox" value="{{$teacher->id}}"  {{ empty(in_array($teacher->id, $teacher_customer)) ? "" : "checked" }}/>{{$teacher->name}}





                            @endforeach
                                @endif

                             </div>
                            </div>







                        </div>
                        <br>


                    </div>
                </div>

                <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>
        {!! Form::close() !!}




    @endif
@endsection



