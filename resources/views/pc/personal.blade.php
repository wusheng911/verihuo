@extends('layouts.app')

@section('styles')
<style>
.form-group{
	margin-bottom:0px;
}
.form-horizontal .control-label {
    padding-top: 7px;
    margin-bottom: 0;
    text-align: left;
}
body{
	font-size:1.4rem;
}
</style>
@endsection
@section('scripts')
<script src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
<script src="https://img.hcharts.cn/highcharts/highcharts-more.js"></script>
<script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
	<script>
$('.am-active').each(function(e){
	$($('.am-active').get(e)).removeClass('am-active');
});
$('.nav-personal').each(function(e){
	$($('.nav-personal').get(e)).addClass('am-active');
});
var yrande = 3.334;
var minScore = 60;
var maxScore = 100;
function selectSchool(e,index){
	var id = $(e).attr('data-id');	
	var name = $(e).attr('data-name');	
	var introduction = $(e).attr('data-introduction');	
	var logo = $(e).attr('data-logo');	
	$('#interestSchool-introduction-' + index).html(introduction);	
	$('#interestSchool-name-' + index).html(name);	
	$('#interestSchool-img-' + index).attr('src',logo);
	$('#interestSchool-input-' + index).attr('value',id);
	var pname = ('#interestSchool-input-' + index);
}
function scoreRande(value){
	var rt = "";
	var t = 0;
	if(value >= 97){
		rt = "A+";
		t = 100;
	}else if(value >= 94){
		rt = "A";
		t = 100 - 1 * yrande;
	}else if(value >= 90){
		rt = "A-";
		t = 100 - 2 * yrande;
	}else if(value >= 87){
		rt = "B+";
		t = 100 - 3 * yrande;
	}else if(value >= 84){
		rt = "B";
		t = 100 - 4 * yrande;
	}else if(value >= 80){
		rt = "B-";
		t = 100 - 5 * yrande;
	}else if(value >= 77){
		rt = "C+";
		t = 100 - 6 * yrande;
	}else if(value >= 74){
		rt = "C";
		t = 100 - 7 * yrande;
	}else if(value >= 70){
		rt = "C-";
		t = 100 - 8 * yrande;
	}else if(value >= 67){
		rt = "D+";
		t = 100 - 9 * yrande;
	}else if(value >= 64){
		rt = "D";
		t = 100 - 10 * yrande;
	}else if(value > 60){
		rt = "D-";
		t = 100 - 11 * yrande;
	}else{
		rt = "F";
		t = 60;
	}
	return [rt,t];
}
$(function() {
	var yAxisValue = ["d-","d","d+","c-","c","c+","b-","b","b+","a-","a","a+"];
	  $.ajax({
	      type: "post",
	      url: "/customer/personalccscore",
	      data: null,
	      dataType: "json",
	      success: function(data){
			  console.log(data);
				var scores = data.scores;
				var advise = data.advises;
				if(advise){
					$('#adviseLeft').html(advise.advise1);
					$('#adviseRight').html(advise.advise2);
				}
				//AlPriceQuery(scores);
				chart1(scores);
				chart2(scores);
	      }});
      });
	function chart2(datas){
		var languageArr = [];
		var visualArr = [];
		var exploringArr = [];
		var commuincationArr = [];
		var loginArr = [];
		var zongheArr = [];
		var c1 =0;
		var c2 =0;
		var c3 =0;
		var c4 =0;
		var c5 =0;
		for(var i=0;i<datas.length;i++){
			languageArr.push(datas[i].c1);	
			visualArr.push(datas[i].c2);	
			exploringArr.push(datas[i].c3);	
			commuincationArr.push(datas[i].c4);	
			loginArr.push(datas[i].c5);	
			if(i<4){
				c1 += datas[i].c1;
				c2 += datas[i].c2;
				c3 += datas[i].c3;
				c4 += datas[i].c4;
				c5 += datas[i].c5;
			}
		}
		if(datas.length >=4){
			zongheArr.push(c1/4);
			zongheArr.push(c2/4);
			zongheArr.push(c3/4);
			zongheArr.push(c4/4);
			zongheArr.push(c5/4);
		}else{
			var tmpLen = datas.length;
			zongheArr.push(c1/tmpLen);
			zongheArr.push(c2/tmpLen);
			zongheArr.push(c3/tmpLen);
			zongheArr.push(c4/tmpLen);
			zongheArr.push(c5/tmpLen);
		}
	$('#chartContainer-2').highcharts({
			chart: {
				polar: true,
				type: 'line'
			},
			title: {
				text: '成绩2',
				x: -80
			},
			pane: {
				size: '80%'
			},
			plotOptions:{
				series:{
					marker:{
						enabled:true
					}
				}
			},
			xAxis: {
				categories: ['Language', 'Visual', 'Exploring', 'Commuincation',
							 'Login'],
				tickmarkPlacement: 'on',
				lineWidth: 0
			},
			yAxis: {
				gridLineInterpolation: 'polygon',
				lineWidth: 0,
				min: 0,
				max:5
			},
			tooltip: {
				shared: true,
				pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>',
				formatter : function (){ // 提示框格式化字符串
				                 var s = '<b>'+this.x+':'+ this.y;
								  return s;
							  },
			},
			legend: {
				align: 'right',
				verticalAlign: 'top',
				y: 70,
				layout: 'vertical',
				enabled:false

			},
			series: [{
				name: 'Language',
				data: zongheArr,
				pointPlacement: 'on'
			}]
		});
	}
	function chart1(datas){
	var yAxisValue = ["","F","D-","D","D+","C-","C ","C+","B-","B","B+","A-","A","A+"];
	var listenArr = [];
	var readArr = [];
	var speakArr = [];
	var writeArr = [];
	var vocabularyArr = [];
	var myDateArr =[];
	var classArr = [];
	for(var i=0;i<datas.length;i++){
		var a1 = scoreRande(datas[i].a1)[1];
		var a2 = scoreRande(datas[i].a2)[1];
		var a3 = scoreRande(datas[i].a3)[1];
		var a4 = scoreRande(datas[i].a4)[1];
		var a5 = scoreRande(datas[i].a5)[1];
		listenArr.push(a1);	
		speakArr.push(a2);	
		readArr.push(a3);	
		writeArr.push(a4);	
		vocabularyArr.push(a5);	
		myDateArr.push(datas[i].at);
		classArr.push("Class "+ (i+1));
	}

var chart = new Highcharts.Chart('chartContainer-1', {
    title: {
        text: '成绩1',
        x: -20
    },
    subtitle: {
        text: '数据来源: Verihuo',
        x: -20
    },
    xAxis: {
		categories: classArr,
			labels:{
				align:"left",
					enabled:false
			}
    },
    yAxis: {
        title: {
            text: '成绩'
	},
		labels: {
                    formatter: function() {
						return  yAxisValue.shift();
					},

                },
		tickInterval: yrande,
		min:60,
		max:100,
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
        }]
    },
    tooltip: {
        valueSuffix: '',
			formatter : function (){ // 提示框格式化字符串
				                 var s = '<b>'+this.x+'</b>';
								 // s += '<br />' + this.series.name + ':' +scoreRande(this.y); 
								  //s += '<br />' + this.series.name + ':' +this.y; 
								 var scores = scoreRande(this.y);
								  s += '<br />' + this.series.name + ':' +scores[0];
								 this.y = scores[1]; 
								  return s;
							  },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
    },
    series: [{
        name: 'Listen',
		data: listenArr,
		color: "#ff0000"
    }, {
        name: 'Speak',
        data: speakArr,
		color: "#ff7d00"
    }, {
        name: 'Read',
        data: readArr,
		color: "#ffff00"
    }, {
        name: 'Write',
        data: writeArr,
		color: "#00ff00"
	}, {
		name: 'Vocabulary',
		data: vocabularyArr,
		color: "#0000ff"
	}]
});
	}
	</script>

@endsection

@section('content')
<div class="col-md-12">
	<div class="col-md-8 col-md-offset-2">
		<h1 class="personal-title" style="margin-top:20px;margin-top:20px;">
					personal information
		</h1>
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">
			<div class="col-md-4">
<div class="panel panel-default">
    <div class="panel-heading">
		  Personal Info
			<a data-toggle="modal" data-target="#personal-info" class="personal-edit-btn" href="#" role="button">编辑</a>
    </div>
    <div class="panel-body personal-info-panel">
		<div class="personal-info-shell">
			<img src="/assets/img/images/1.jpg" class="img-thumbnail img-responsive center-block am-circle" alt="Responsive image" style="width:240px;height:240px;">
			<h3 class="text-center">{{$user->user_name}}</h3>
			<h4 class="text-center">2018</h4>
		</div>
    </div>
</div>
			</div>
			<div class="col-md-4">
<div class="panel panel-default" >
    <div class="panel-heading">
		Background  Information
			<a data-toggle="modal" data-target="#personal-bg" class="personal-edit-btn" href="#" role="button">编辑</a>
    </div>
    <div class="panel-body personal-info-panel">
		<div class="personal-info-shell">
			  <div class="form-group form-horizontal">
				<label class="col-sm-2 control-label">From</label>
				<div class="col-sm-10">
				  <p class="form-control-static">shanghai</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-2 control-label">Age</label>
				<div class="col-sm-10">
				  <p class="form-control-static">18</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">Gender</label>
				<div class="col-sm-9">
				  <p class="form-control-static">
					@if($user->sex == 1)
						Male	
					@elseif($user->sex == 2)
						Female
					@else

					@endif
					</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10">
				  <p class="form-control-static">{{$user->email}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">Phone</label>
				<div class="col-sm-9">
				  <p class="form-control-static">{{$user->phone}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Studying at</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user->school_name}}</p>
				</div>
			  </div>
		</div>
    </div>
</div>
			</div>
			<div class="col-md-4">
<div class="panel panel-default">
    <div class="panel-heading">
		Scores
			<a data-toggle="modal" data-target="#personal-score" style="float:right;" class="personal-edit-btn" href="#" role="button">编辑</a>
    </div>
    <div class="panel-body personal-info-panel">
			  <div class="form-group form-horizontal">
				<label class="col-sm-7 control-label">Graduate Year</label>
				<div class="col-sm-5">
				  <p class="form-control-static">{{$user_score->graduate_year}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">GPA</label>
				<div class="col-sm-9">
				  <p class="form-control-static">{{$user_score->gpa}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Class Rank</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_score->class_rank}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-5 control-label">IB Score</label>
				<div class="col-sm-7">
				  <p class="form-control-static">{{$user_score->ib_score}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">TOEFL</label>
				<div class="col-sm-9">
				  <p class="form-control-static">L:{{$user_score->toefl_listening}}S:{{$user_score->toefl_speaking}}R:{{$user_score->toefl_reading}}W:{{$user_score->toefl_writting}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">IELTS</label>
				<div class="col-sm-9">
				  <p class="form-control-static">L:{{$user_score->ielts_listening}}S:{{$user_score->ielts_speaking}}R:{{$user_score->ielts_reading}}W:{{$user_score->ielts_writting}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">SATI</label>
				<div class="col-sm-9">
				  <p class="form-control-static">{{$user_score->sat_i_math}},{{$user_score->sat_i_critical_reading}},{{$user_score->sat_i_writing}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">SAT2</label>
				<div class="col-sm-9">
				  <p class="form-control-static">18888888888</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-3 control-label">ACT</label>
				<div class="col-sm-9">
				  <p class="form-control-static">{{$user_score->act}}</p>
				</div>
			  </div>
    </div>
</div>
			</div>
		</div>	
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-6">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Experience Information
					<a data-toggle="modal" data-target="#personal-exp1" class="personal-edit-btn" href="#" role="button">编辑</a>
				</div>
				<div class="panel-body" >
					<div class="col-md-12">
						<h2 class="text-center">
							{{$user_exp1->coursework}}
						</h2>
					</div>
					  <div class="form-group form-horizontal">
						<label class="col-sm-7 control-label">AP/Honors Courses Taken</label>
						<div class="col-sm-5">
						  <p class="form-control-static">{{$user_exp1->aphonors}}</p>
						</div>
					  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-8 control-label">Academic Honors or Awards</label>
				<div class="col-sm-4">
				  <p class="form-control-static">{{$user_exp1->awards}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-5 control-label">First Language</label>
				<div class="col-sm-7">
				  <p class="form-control-static">{{$user_exp1->first_language}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-9 control-label">Any family members study abroad</label>
				<div class="col-sm-3">
				  <p class="form-control-static">{{$user_exp1->family}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-7 control-label">College-level coursework</label>
				<div class="col-sm-5">
				  <p class="form-control-static">{{$user_exp1->coursework}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-7 control-label"></label>
				<div class="col-sm-5">
				  <p class="form-control-static"></p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-7 control-label"></label>
				<div class="col-sm-5">
				  <p class="form-control-static"></p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-7 control-label"></label>
				<div class="col-sm-5">
				  <p class="form-control-static"></p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-7 control-label"></label>
				<div class="col-sm-5">
				  <p class="form-control-static"></p>
				</div>
			  </div>
				</div>
			</div>
		</div>
		<div class="col-md-6">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Experience Information
					<a data-toggle="modal" data-target="#personal-exp2" class="personal-edit-btn" href="#" role="button">编辑</a>
				</div>
				<div class="panel-body" >
					<div class="col-md-12">
						<h2 class="text-center">
							{{$user_exp2->arts}}
						</h2>
					</div>
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Competitions won</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$user_exp2->won}}</p>
						</div>
					  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-5 control-label">Sports/Activities</label>
				<div class="col-sm-7">
				  <p class="form-control-static">{{$user_exp2->activite}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Leadership positions</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_exp2->leadership}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-5 control-label">Music Experience</label>
				<div class="col-sm-7">
				  <p class="form-control-static">{{$user_exp2->arts}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Community Experiences</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_exp2->community}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Research Experience</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_exp2->research}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Work Experience</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_exp2->work}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Internship Experience</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_exp2->intership}}</p>
				</div>
			  </div>
			  <div class="form-group form-horizontal">
				<label class="col-sm-6 control-label">Summer programs</label>
				<div class="col-sm-6">
				  <p class="form-control-static">{{$user_exp2->summer}}</p>
				</div>
			  </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Interested Schools 
					<a data-toggle="modal" data-target="#personal-school" class="personal-edit-btn" href="#" role="button">编辑</a>
				</div>
				<div class="panel-body" >
					<div class="row">
						@foreach($schoolList as $key =>$value)
						  <div class="col-md-2">
							<a data-toggle="modal" data-target="#personal-school" href="#" class="thumbnail">
							  <img style="width:120px;height:70px;" src="{{$value->logo}}" alt="{{$value->name}}">
							</a>
						  </div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Academic Progress Report
				</div>
				<div class="panel-body" >
	<div id="adviseLeft" class="col-md-3" style="height:300px;border-style: groove;
padding:20px;">
</div>	
	<div class="col-md-9" id="chartContainer-1" style="height:300px;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Student Assessment Report
				</div>
				<div class="panel-body" >
	<div class="col-md-7" id="chartContainer-2" style="height:300px;"></div>
	<div id="adviseRight" class="col-md-5" style="height:300px;border-style: groove;
padding:20px;">
</div>	
				</div>
			</div>
		</div>
	</div>
@if(!isset($report))
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Report
				</div>
				<div class="panel-body" >
						<div class="report-title">Report</div>	
						<div class="report-user-info">
							<div class="report-user-professor"><span>Professor:</span><span>哼哼哈嘿</span></div>
							<div class="report-user-name"><span>Name:</span><span>哼哼哈嘿</span></div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								<div class="report-left">	
									<div class="report-left-top">app.verihuoGrade</div>	
									<div class="report-left-center">
										<div class="report-left-center-content"><div class="report-box">ads</div></div>
									</div>	
									<div class="report-left-bottom">什么样的刀枪棍棒我都耍得有模有样快使用双截棍哼哼哈嘿kuaishiyongshuangjiegunhenghenghahei</div>
								</div>
							</div>
							<div class="col-md-9">
								<div class="report-right">	
									<div class="report-right-top">My Grade</div>	
									<div class="report-right-center">
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">ads</div>
											</div>
											<div class="report-right-ceil-text">哼哼哈嘿</div>
										</div>
									</div>
									<div class="report-right-bottom">Test My Name Is Heng Heng Ha HeiTest My Name Is Heng Heng Ha HeiTest My Name Is Heng Heng Ha HeiTest My Name Is Heng Heng Ha HeiTest My Name Is Heng Heng Ha Hei</div>	
								</div>
							</div>

							
						</div>
						<div class="col-md-12 report-acceptance">
							<div class="col-md-3">
								<div class="report-acceptance-title">我是一个大苹果</div>	
								<div class="col-md-12 report-like" >
									<div><span class="report-like-left">test1</span><span class="report-like-right">test2</span></div>	
									<div><span class="report-like-left">test3</span><span class="report-like-right">test4</span></div>	
									<div><span class="report-like-left">test5</span><span class="report-like-right">test6</span></div>	
									<div><span class="report-like-left">test7</span><span class="report-like-right">test8</span></div>	
									<div><span class="report-like-left">test9</span><span class="report-like-right">test10</span></div>	
								</div>
							</div>
							<div class="col-md-9">
								<div class="report-tip-title">Tips</div>	
								<div class="col-md-12 report-tips">
									<div class="col-md-2 report-tips-left"><span class="report-tips-left-content">1</span></div>
									<div class="col-md-10 report-tips-right"><span class="report-tips-right-content">ttest1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1est1</span></div>
								</div>
								<div class="col-md-12 report-tips">
									<div class="col-md-2 report-tips-left"><span class="report-tips-left-content">1</span></div>
									<div class="col-md-10 report-tips-right"><span class="report-tips-right-content">ttest1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1est1</span></div>
								</div>
								<div class="col-md-12 report-tips">
									<div class="col-md-2 report-tips-left"><span class="report-tips-left-content">1</span></div>
									<div class="col-md-10 report-tips-right"><span class="report-tips-right-content">ttest1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1test1est1</span></div>
								</div>
							</div>
						</div>
						<div class="col-md-12 report-school">
							<div class="col-md-3">
								<div class="report-school-left-title">
									Recommend School	
								</div>
								<div class="col-md-12">
									<span class="report-school-content">qing hua da xue</span>
									<span class="report-school-content">qing hua da xue</span>
									<span class="report-school-content">qing hua da xue</span>
								</div>
							</div>
							<div class="col-md-9">
								<div class="report-plan-title">
									Action Plan
								</div>
								<div class="report-plan-content">
									Action PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction PlanAction Plan
								</div>
							</div>
						</div>
				</div>	
			</div>
			</div>
		</div>
@else
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Report
				</div>
				<div class="panel-body" >
						<div class="report-title">Report</div>	
						<div class="report-user-info">
							<div class="report-user-professor"><span>Professor:</span><span>{{$report->professor}}</span></div>
							<div class="report-user-name"><span>Name:</span><span>{{$user->user_name}}</span></div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								<div class="report-left">	
									<div class="report-left-top">app.verihuoGrade</div>	
									<div class="report-left-center">
										<div class="report-left-center-content"><div class="report-box">{{$report->final}}</div></div>
									</div>	
									<div class="report-left-bottom">test final</div>
								</div>
							</div>
							<div class="col-md-9">
								<div class="report-right">	
									<div class="report-right-top">My Grade</div>	
									<div class="report-right-center">
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->gpa_grade}}</div>
											</div>
											<div class="report-right-ceil-text">GPA/Grades</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->test_score}}</div>
											</div>
											<div class="report-right-ceil-text">Test Scores</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->course}}</div>
											</div>
											<div class="report-right-ceil-text">Courses</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->award}}</div>
											</div>
											<div class="report-right-ceil-text">Honors@Awards</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->activity}}</div>
											</div>
											<div class="report-right-ceil-text">Activity</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->work_exp}}</div>
											</div>
											<div class="report-right-ceil-text">Work</div>
										</div>
										<div class="report-right-ceil">
											<div class="report-right-ceil-content"> 
												<div class="report-box">{{$report->research_exp}}</div>
											</div>
											<div class="report-right-ceil-text">Research</div>
										</div>
									</div>
									<div class="report-right-bottom">Based on student response</div>	
								</div>
							</div>

							
						</div>
						<div class="col-md-12 report-acceptance">
							<div class="col-md-3">
								<div class="report-acceptance-title">Ru xue gai lv</div>	
								<div class="col-md-12 report-like" >
									<div><span class="report-like-left">{{$report->fschool1}}</span><span class="report-like-right">{{$report->fschool1_percent}}</span></div>	
									<div><span class="report-like-left">{{$report->fschool2}}</span><span class="report-like-right">{{$report->fschool2_percent}}</span></div>	
									<div><span class="report-like-left">{{$report->fschool3}}</span><span class="report-like-right">{{$report->fschool3_percent}}</span></div>	
									<div><span class="report-like-left">{{$report->fschool4}}</span><span class="report-like-right">{{$report->fschool4_percent}}</span></div>	
									<div><span class="report-like-left">{{$report->fschool5}}</span><span class="report-like-right">{{$report->fschool5_percent}}</span></div>	
								</div>
							</div>
							<div class="col-md-9">
								<div class="report-tip-title">Tips</div>	
								<div class="col-md-12 report-tips">
									<div class="col-md-2 report-tips-left"><span class="report-tips-left-content">1</span></div>
									<div class="col-md-10 report-tips-right"><span class="report-tips-right-content">{{$report->tip1}}</span></div>
								</div>
								<div class="col-md-12 report-tips">
									<div class="col-md-2 report-tips-left"><span class="report-tips-left-content">1</span></div>
									<div class="col-md-10 report-tips-right"><span class="report-tips-right-content">{{$report->tip2}}</span></div>
								</div>
								<div class="col-md-12 report-tips">
									<div class="col-md-2 report-tips-left"><span class="report-tips-left-content">1</span></div>
									<div class="col-md-10 report-tips-right"><span class="report-tips-right-content">{{$report->tip3}}</span></div>
								</div>
							</div>
						</div>
						<div class="col-md-12 report-school">
							<div class="col-md-3">
								<div class="report-school-left-title">
									Recommend School	
								</div>
								<div class="col-md-12">
									<span class="report-school-content">{{$report->recommend_school1}}</span>
									<span class="report-school-content">{{$report->recommend_school2}}</span>
									<span class="report-school-content">{{$report->recommend_school3}}</span>
								</div>
							</div>
							<div class="col-md-9">
								<div class="report-plan-title">
									Action Plan
								</div>
								<div class="report-plan-content">
									{{$report->action_plan}} 
								</div>
							</div>
						</div>
				</div>	
			</div>
			</div>
		</div>

@endif
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="col-md-12">	
			<div class="panel panel-default">
				<div class="panel-heading">
					Verihuo Archive 
					<a data-toggle="modal" data-target="#personal-archive" class="personal-edit-btn" href="#" role="button">编辑</a>
				</div>
				<div class="panel-body" >
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Student ID</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->customer_id}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Parents name</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->parents_name}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Level</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->level}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Test Score</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->test_score}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Parents Phone</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->parents_phone}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Status</label>
						<div class="col-sm-7">
						  <p class="form-control-static">
							@if($archive->status == 1)
								active
							@elseif($archive->status == 2)
								pending
							@elseif9$archive->status ==3)
								7closed
							@endif
						  </p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Onboard date</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->onboard}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Advisor</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->advisor}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Course</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->course_id}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Points</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->points}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Contract</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->contract}}</p>
						</div>
					  </div>
					</div>
					<div class="col-md-4 col-md-offset-0">
					  <div class="form-group form-horizontal">
						<label class="col-sm-5 control-label">Awards</label>
						<div class="col-sm-7">
						  <p class="form-control-static">{{$archive->awards}}</p>
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




	<!--个人信息1 --!>
      <div id="personal-info" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Personal Info</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@info')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                        <div class="panel-body">
                              <div class="form-group">
                                  <label for="">姓名</label>
                                  <input class="form-control" name="info[name]" type="text" value="{{$user->user_name}}" placeholder="">
                              </div>
							<br>
                              <div class="form-group">
							  <label for="">毕业年份</label>
									<select class="form-control" name="info[graduate_year]" >
										@for($i=1990;$i<2010;$i++)
												<option value="{{$i}}" selected="selected">{{$i}}</option>
										@endfor
									</select>
								</div>
                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>



	<!--背景信息--!>
      <div id="personal-bg" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Personal Info</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@bg')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                        <div class="panel-body">
                              <div class="form-group">
                                  <label for="">From</label>
                                  <input class="form-control" name="bg[from]" type="text" placeholder="">
                              </div>
                              <div class="form-group">
							  <label for="">Age</label>
									<select class="form-control" name="bg[age]" >
										@for($i=10;$i<100;$i++)
												<option value="{{$i}}" selected="selected">{{$i}}</option>
										@endfor
									</select>
								</div>
                              <div class="form-group">
							  <label for="">Gender</label>
									<select class="form-control" name="bg[gender]" >
										@if($user->sex == 1)
												<option value="1"  selected="selected" >male</option>
												<option value="2">female</option>
										@elseif($user->sex == 2)
												<option value="1" >male</option>
												<option value="2" selected="selected">female</option>

										@else
												<option value="1">male</option>
												<option value="2">female</option>
										@endif
									</select>
								</div>
                              <div class="form-group">
                                  <label for="">Email</label>
                                  <input class="form-control" name="bg[email]" value="{{$user->email}}" type="text" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Phone</label>
                                  <input class="form-control" name="bg[phone]" type="text" value="{{$user->phone}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Studying At</label>
                                  <input class="form-control" name="bg[studyingat]" value="{{$user->school_name}}"  type="text" placeholder="">
                              </div>
							<br>
                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>




	<!--得分信息1 --!>
      <div id="personal-score" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Personal Score</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@score')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                        <div class="panel-body">
                              <div class="form-group">
							  <label for="">Graduate Year</label>
									<select class="form-control" name="score[graduate_year]" >
										@for($i=1990;$i<2010;$i++)
												<option value="{{$i}}" selected="selected">{{$i}}</option>
										@endfor
									</select>
								</div>
                              <div class="form-group">
                                  <label for="">GPA</label>
                                  <input class="form-control" name="score[gpa]" value="{{$user_score->gpa}}"  type="text" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Class Rank</label>
                                  <input class="form-control" name="score[class_rank]" type="text" value="{{$user_score->class_rank}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">IB Rank</label>
                                  <input class="form-control" name="score[ib_score]" type="text" value="{{$user_score->ib_score}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">ACT</label>
                                  <input class="form-control" name="score[act]" type="text" value="{{$user_score->act}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">TOEFL</label>
										<div class="row">
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												听
											  </span>
											  <input type="text" class="form-control" name="score[toefl_listening]" value="{{$user_score->toefl_listening}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												说	
											  </span>
											  <input type="text" class="form-control" name="score[toefl_speaking]" value="{{$user_score->toefl_speaking}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												读	
											  </span>
											  <input type="text" class="form-control" name="score[toefl_reading]" value="{{$user_score->toefl_reading}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												写	
											  </span>
											  <input type="text" class="form-control" name="score[toefl_writting]" value="{{$user_score->toefl_writting}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										</div>
                              </div>
                              <div class="form-group">
                                  <label for="">IELTS</label>
										<div class="row">
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												听
											  </span>
											  <input type="text" class="form-control" name="score[ielts_listening]" value="{{$user_score->ielts_listening}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												说	
											  </span>
											  <input type="text" class="form-control" name="score[ielts_speaking]" value="{{$user_score->ielts_listening}}" aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												读	
											  </span>
											  <input type="text" class="form-control" name="score[ielts_reading]" value="{{$user_score->ielts_reading}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-3">
											<div class="input-group">
											  <span class="input-group-addon">
												写	
											  </span>
											  <input type="text" class="form-control" name="score[ielts_writting]" value="{{$user_score->ielts_writting}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										</div>
                              </div>
                              <div class="form-group">
                                  <label for="">SAT1</label>
										<div class="row">
										  <div class="col-lg-4">
											<div class="input-group">
											  <span class="input-group-addon">
												Math	
											  </span>
											  <input type="text" class="form-control" name="score[sat_i_math]" value="{{$user_score->sat_i_math}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-4">
											<div class="input-group">
											  <span class="input-group-addon">
												Critical	
											  </span>
											  <input type="text" class="form-control" name="score[sat_i_critical_reading]"  value="{{$user_score->sat_i_critical_reading}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										  <div class="col-lg-4">
											<div class="input-group">
											  <span class="input-group-addon">
												Reading	
											  </span>
											  <input type="text" class="form-control" name="score[sat_i_writing]" value="{{$user_score->sat_i_writing}}"  aria-label="...">
											</div><!-- /input-group -->
										  </div><!-- /.col-lg-6 -->
										</div>
                              </div>
                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>




	<!--个人经历1 --!>
      <div id="personal-exp1" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Experience Information</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@exp1')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                        <div class="panel-body">
                              <div class="form-group">
                                  <label for="">AP/Honors Courses Taken</label>
                                  <input class="form-control" name="exp1[aphonors]" type="text" value="{{$user_exp1->aphonors}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Academic Honors or Awards</label>
                                  <input class="form-control" name="exp1[awards]" type="text" value="{{$user_exp1->awards}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">First Language</label>
                                  <input class="form-control" name="exp1[first_language]" type="text" value="{{$user_exp1->first_language}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Any family members study abroad</label>
                                  <input class="form-control" name="exp1[family]" type="text" value="{{$user_exp1->family}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">College-level coursework</label>
                                  <input class="form-control" name="exp1[coursework]" type="text" value="{{$user_exp1->coursework}}"  placeholder="">
                              </div>
                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>



	<!--个人经历2 --!>
      <div id="personal-exp2" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Experience Information</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@exp2')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                        <div class="panel-body">
                              <div class="form-group">
                                  <label for="">Competitions won</label>
                                  <input class="form-control" name="exp2[won]" type="text" value="{{$user_exp2->won}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Sports/Activites</label>
                                  <input class="form-control" name="exp2[activite]" type="text" value="{{$user_exp2->activite}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Leadership positions</label>
                                  <input class="form-control" name="exp2[leadership]" type="text" value="{{$user_exp2->leadership}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Music Experience</label>
                                  <input class="form-control" name="exp2[arts]" type="text" value="{{$user_exp2->arts}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Community Experiences</label>
                                  <input class="form-control" name="exp2[community]" type="text"  value="{{$user_exp2->community}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Research Experience</label>
                                  <input class="form-control" name="exp2[research]" type="text" value="{{$user_exp2->research}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Work Experience</label>
                                  <input class="form-control" name="exp2[work]" type="text" value="{{$user_exp2->work}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Internship Experience</label>
                                  <input class="form-control" name="exp2[intership]" type="text" value="{{$user_exp2->intership}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Summer Experience</label>
                                  <input class="form-control" name="exp2[summer]" type="text" value="{{$user_exp2->summer}}"  placeholder="">
                              </div>
                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>





	<!--兴趣学校 --!>
      <div id="personal-school" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Interested Schools</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@interestSchool')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
						
                        <div class="panel-body">
								@php
									$schoolIndex = 1;
								@endphp	
							@foreach($schoolList as $key => $value)
								<div class="panel panel-default">
									<div class="panel-heading"> <h3 class="panel-title">Insterested School  {{$schoolIndex}}</h3> </div>
									<div class="panel-body form-horizontal">
												  <input id="interestSchool-input-{{$schoolIndex}}" style="display:none;" class="form-control" name="school-{{$schoolIndex}}" type="text" value=""  placeholder="">
											<div class="col-md-4">
												<img  id="interestSchool-img-{{$schoolIndex}}" class="img-rounded" style="width:120px;height:120px;" src="{{$value->logo}}" >	
											</div>
											<div class="col-md-5">
												  <div class="form-group form-horizontal">
													<div class="col-sm-12">
													  <p id="interestSchool-name-{{$schoolIndex}}" class="form-control-static">{{$value->name}}</p>
													</div>
												  </div>
												  <div class="form-group form-horizontal">
													<div class="col-sm-12">
													  <p id="interestSchool-introduction-{{$schoolIndex}}" style="width:120px;height:105px;overflow:hidden" class="form-control-static">{{$value->introduction}}</p>
													</div>
												  </div>
											</div>
											<div class="col-md-3  pre-scrollable" style="height:140px;">
												<ul class="list-group">
													@foreach($schools as $key => $value)
														<a ><li data-id="{{$value->id}}" data-logo="{{$value->logo}}" data-name="{{$value->name}}" data-introduction="{{$value->introduction}}"  onclick="selectSchool(this,{{$schoolIndex}})"  class="school-list-item">{{$value->name}}</li></a>
													@endforeach
												</ul>					
											</div>
									</div>
								</div>
								@php
									$schoolIndex++;
								@endphp	
							@endforeach
							@for($i = $schoolIndex; $i<7; $i++)
								<div class="panel panel-default">
									<div class="panel-heading"> <h3 class="panel-title">Insterested School  {{$i}}</h3> </div>
									<div class="panel-body form-horizontal">
												  <input id="interestSchool-input-{{$i}}" style="display:none;" class="form-control" name="school-{{$i}}" type="text" value=""  placeholder="">
											<div class="col-md-4">
												<img  id="interestSchool-img-{{$i}}" class="img-rounded" style="width:120px;height:120px;" src="" >	
											</div>
											<div class="col-md-5">
												  <div class="form-group form-horizontal">
													<div class="col-sm-12">
													  <p id="interestSchool-name-{{$i}}" class="form-control-static"></p>
													</div>
												  </div>
												  <div class="form-group form-horizontal">
													<div class="col-sm-12">
													  <p id="interestSchool-introduction-{{$i}}" style="width:120px;height:105px;overflow:hidden" class="form-control-static"></p>
													</div>
												  </div>
											</div>
											<div class="col-md-3  pre-scrollable" style="height:140px;">
												<ul class="list-group">
													@foreach($schools as $key => $value)
														<a ><li data-id="{{$value->id}}" data-logo="{{$value->logo}}" data-name="{{$value->name}}" data-introduction="{{$value->introduction}}"  onclick="selectSchool(this,{{$i}})"  class="school-list-item">{{$value->name}}</li></a>
													@endforeach
												</ul>					
											</div>
									</div>
								</div>
							@endfor

                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
				 </div>
			 </div>
     </div>

	<!--Archive --!>
      <div id="personal-archive" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <button class="close" data-dismiss="modal">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-title">
                      <h1 class="text-center">Archive</h1>
                  </div>
                  <div class="modal-body">
                      <form class="form-group" method="post"  action="{{ action('CustomerController@archive')}}">
<input type="hidden" name="_token"         value="{{csrf_token()}}"/>
                        <div class="panel-body">
                              <div class="form-group">
                                  <label for="">Parents Name</label>
                                  <input class="form-control" name="archive[parents_name]" type="text" value="{{$archive->parents_name}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Level</label>
                                  <input class="form-control" name="archive[level]" type="text" value="{{$archive->level}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Test Score</label>
                                  <input class="form-control" name="archive[test_score]" type="text" value="{{$archive->test_score}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Parents Phone</label>
                                  <input class="form-control" name="archive[parents_phone]" type="text"  value="{{$archive->parents_phone}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Status</label>
									<select class="form-control" name="archive[status]" >
											@if($archive->status == 1)
												<option value="1" selected="selected">active</option>
												<option value="2" >pending</option>
												<option value="3" >closed</option>
											@elseif($archive->status == 2)
												<option value="1" >active</option>
												<option value="2" selected="selected">pending</option>
												<option value="3" >closed</option>
											@elseif($archive->status == 3)
												<option value="1" >active</option>
												<option value="2" >pending</option>
												<option value="3" selected="selected">closed</option>
											@else
												<option value="1" selected="selected">active</option>
												<option value="2" >pending</option>
												<option value="3" >closed</option>
											@endif
									</select>
                              </div>
                              <div class="form-group">
                                  <label for="">Onboard Date</label>
                                  <input class="form-control" name="archive[onboard]" type="text" value="{{$archive->onboard}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Advisor</label>
                                  <input class="form-control" name="archive[advisor]" type="text" value="{{$archive->advisor}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Course</label>
									<select class="form-control" name="archive[course_id]" >
										@php
											$courseArr = ['1'=>'study abroad','2'=>'business english','3'=>'kids global','4'=>'primary','5'=>'junior hight','6'=>'adults english']
										@endphp	
										@foreach($courseArr as $key => $value)
											@if($archive->course_id == $key)
												<option value="{{$key}}" selected="selected">{{$value}}</option>
											@else
												<option value="{{$key}}">{{$value}}</option>
											@endif
										@endforeach
										
									</select>
                              </div>
                              <div class="form-group">
                                  <label for="">Points</label>
                                  <input class="form-control" name="archive[points]" type="text" value="{{$archive->points}}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Contract</label>
                                  <input class="form-control" name="archive[contract]" type="text" value="{{$archive->contract}}"  placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="">Awards</label>
                                  <input class="form-control" name="archive[awards]" type="text" value="{{$archive->awards}}"  placeholder="">
                              </div>
                        </div>
							  <button class="personal-edit-sure-btn btn btn-lg btn-success" type="submit">提交</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>
@endsection
