<?php 
namespace App\Components;

class HtmlComponent{
	public static function tabs($contents=[])
	{
		$optionsMenu = "";
		$optionsContent = "";
		$first = true;
		foreach ($contents as $title => $content) {
			$optionsMenu.="<li ".($first?"class='active'":'')."><a href='#' data-toggle='tab'>$title</a></li>";
			$optionsContent.="<div class='tab-pane ".($first?'active':'')."'>$content</div>";
			$first=false;
		}
		return <<<EOF
		<div class="row dinamic-tab">
		<div class="col-xs-3 col-menu">
			<ul class='nav nav-pills nav-stacked'>
			$optionsMenu
			</ul>
		</div>
		<div class="col-xs-9 col-content">
			<div class="tab-content">
			$optionsContent
			</div>
		</div>
		</div>
EOF;
	}
	public function getMonths($month=false,$short=false)
	{
		$months = [''];
		$months[]='Enero';
		$months[]='Febrero';
		$months[]='Marzo';
		$months[]='Abril';
		$months[]='Mayo';
		$months[]='Junio';
		$months[]='Julio';
		$months[]='Agosto';
		$months[]='Septiembre';
		$months[]='Octubre';
		$months[]='Noviembre';
		$months[]='Diciembre';
		if($month!==false) return $short?mb_substr($months[$month], 0,3,'UTF-8'):$months[$month];
		return $months;
	}
	public function getDays($day=false,$short=false)
	{
		$days = [];
		$days[]='Domingo';
		$days[]='Lunes';
		$days[]='Martes';
		$days[]='Miércoles';
		$days[]='Jueves';
		$days[]='Viernes';
		$days[]='Sábado';
		if($day!==false) return $short?mb_substr($days[$day], 0,3,'UTF-8'):$days[$day];
		return $days;
	}
	public function inputsByLangs($model,$label,$attr,$extraClass="",$extraParentClass="")
	{
		$langs = SettingsComponent::get('activeLangs');
		$html = "";
		foreach ($langs as $key) {
			$method = "get$attr";
			$aux = $model->$method($key);
			$auxKey = strtoupper($key);
			$html.=<<<EOF
			<div class="form-group $extraParentClass">
		  		<label>$label ($auxKey)</label>
		  		<input type="text" name="{$attr}[$key]" class="form-control $extraClass" value="$aux">
			</div>
EOF;
		}
		return $html;
	}
	public function textareasByLangs($model,$label,$attr,$extraClass="",$extraParentClass="")
	{
		$langs = SettingsComponent::get('activeLangs');
		$html = "";
		foreach ($langs as $key) {
			$method = "get$attr";
			$aux = $model->$method($key);
			$auxKey = strtoupper($key);
			$html.=<<<EOF
			<div class="form-group $extraParentClass">
		  		<label>$label ($auxKey)</label>
		  		<textarea rows='3' name="{$attr}[$key]" class="form-control $extraClass">$aux</textarea>
			</div>
EOF;
		}
		return $html;
	}
}