<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gantt</title>
</head>
<body>

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/0033cd50fd.js" crossorigin="anonymous"></script>
    <!-- Gantt -->
    <script type="text/javascript" src="/gantt/dhtmlxgantt.js"></script>  
    <link rel="stylesheet" href="/gantt/dhtmlxgantt.css">
	<!-- Eloquent -->
    <script type="text/javascript" src="/js/eloquent/{{ config('app.env') }}.js"></script>

    <style type="text/css">

        html, body {
            height: 100%;
            padding :0;
            margin: 0;
            overflow: hidden;
        }

		.gantt_task_cell.week_end {
			background-color: #EFF5FD;
		}

		.gantt_task_row.gantt_selected .gantt_task_cell.week_end {
			background-color: #F8EC9C;
		}

		.gantt_control {
			font-size: 15px;
			color: #656565;
		    height: 60px;
		    display: flex;
		    align-items: center;
		    justify-content: space-between;
		    padding-right: 20px;
		    padding-left: 20px;		    
		}

		.cursor-pointer {
			cursor: pointer;
		}

		.gantt-inline-actions {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 15px;
			color: #656565;
		}

		.gantt-inline-action-button-disabled {
		    pointer-events: none;
		    opacity: 0;			
		}

		.gantt-inline-actions .fas {
			margin-right: 5px;
		}

		.gantt-tree-icon {
			color: #A8A6A6;
			margin-right: 5px;
			font-size: 15px;
		}

		.gantt_tree_icon.gantt_close, .gantt_tree_icon.gantt_open{
		    background: rgba(2,2,2,.01);
		    width: initial!important;
		    vertical-align: initial!important;
		    height: initial!important;
		}

		.gantt-logo {
		    height: 20px;
		}


		.baseline {
			position: absolute;
			margin-top: -6px;
			height: 6px;
    		background: repeating-linear-gradient(-45deg, rgb(106, 153, 78), rgb(106, 153, 78) 10px, rgba(106, 153, 78, 0.5) 10px, rgba(106, 153, 78, 0.5) 20px);
		}

		/* move task lines upper */
		/*
		.gantt_task_line, .gantt_line_wrapper {
			margin-top: -9px;
		}

		.gantt_side_content {
			margin-bottom: 7px;
		}

		.gantt_task_link .gantt_link_arrow {
			margin-top: -12px
		}

		.gantt_side_content.gantt_right {
			bottom: 0;
		}
		*/

	</style>

<div class="gantt_control">
	<div>
		<i class="fas fa-search-plus fa-fw fa-border cursor-pointer" onclick="zoomIn()"></i>
		<i class="fas fa-search-minus fa-fw fa-border cursor-pointer" onclick="zoomOut()"></i>
		<i class="fas fa-undo fa-fw fa-border cursor-pointer" onclick="gantt.undo()"></i>
		<i class="fas fa-redo fa-fw fa-border cursor-pointer" onclick="gantt.redo()"></i>
	</div>
	<div>
		<img class="gantt-logo" src="/storage/login-logo.svg">
	</div>
</div>

<div id="gantt_here" style="width:100%; height:calc(100vh - 60px);"></div>

<script type="text/javascript">

	window.token = '{{ \Auth::user()->api_token }}';// '0ekJMDXXopEwcqWYwgColVLGvCMVMc08gIrzKcv6RHdkFBTZKU3rmprWSKGa';
	let role = {
		code: '{{ \Auth::user()->userProfile->code }}',
	};

	// Filters
	let searchParams = new URLSearchParams(window.location.search)
	let projectId;
	if (searchParams.has('projectId')) {
	  let s = _.parseInt(searchParams.get('projectId'));
	  if (!isNaN(s)) {
	    if (s > 0) {
	      projectId = s;
	    }
	  }
	}

	// Plugins
	gantt.plugins({
	    auto_scheduling: true,
	    undo: true,
	    marker: true,
	});	

	// Dates format
	gantt.config.date_format = "%Y-%m-%d";

	// Auto scheduling
	gantt.config.auto_scheduling = true;
	gantt.config.auto_scheduling_strict = true;
	gantt.config.auto_scheduling_compatibility = true;
	gantt.config.auto_scheduling_initial = true;
	gantt.config.drag_project = true;

	// Work time
	gantt.config.work_time = true;
	gantt.config.duration_unit = "day";	

	// Reordering
	gantt.config.order_branch = true;

	// Disable progress
	gantt.config.drag_progress = false;

	// Bar height
	gantt.config.bar_height = 25;

	// Auto fit gantt when dragging tasks outside of the project boundaries
	gantt.config.fit_tasks = true;

	// Reaonly property
	gantt.config.readonly_property = "readonly";

	// Default colors
	let defaultTaskColor = '#409AEE';
	let defaultSummaryColor = '#BBB';

	gantt.attachEvent("onParse", function () {
		gantt.eachTask(function (task) {
		    if (task.type == gantt.config.types.project || task.parent == 0) {
		    	task.color = defaultSummaryColor;
		    }
		});
	});

	gantt.attachEvent("onBeforeTaskUpdate", function(id, newTask) {
		// Clear work and assignee fields for the summary tasks
	    if (newTask.type == gantt.config.types.project) {
	    	newTask.color = defaultSummaryColor;
	    } else {
	    	newTask.color = getTaskColor(newTask.id);
	    }
	});

	gantt.attachEvent("onBeforeTaskAdd", function (id, task) {
    	task.color = getTaskColor(task.id);
	});	

	function getTaskColor(id)
	{
		let task = gantt.getTask(id);
		let project = getTaskRoot(id);
		let customer = _.find(customers, ['id', project.customer]);
		let customerColor = defaultTaskColor;
		if (customer && customer.color) {
			customerColor = customer.color;
		}
		return task.type == gantt.config.types.project ? defaultSummaryColor : customerColor;
	}

	function getTaskRoot(id)
	{
		let task = gantt.getTask(id);		
		if (task.parent) {
			return getTaskRoot(task.parent)
		} else {
			return task;
		}
	}

	// Sync color from project customer to project tasks
	gantt.attachEvent("onParse", function () {
		gantt.eachTask(function (task) {
			task.color = getTaskColor(task.id);
		});
	});

	// Today marker
	var dateToStr = gantt.date.date_to_str(gantt.config.task_date);
	var markerId = gantt.addMarker({
	    start_date: new Date(),
	    css: "today",
	    text: "Now",
	    title: dateToStr( new Date())
	});

	// Tree icons
	// Summary tasks
	gantt.templates.grid_folder = function(item) {
		let html = item.$open ?
			'<i class="far fa-fw fa-folder-open"></i>' :
			'<i class="far fa-fw fa-folder"></i>';
		html = '<div class="gantt-tree-icon">' + html + '</div>';
		return html;
	};
	// Regular tasks
	gantt.templates.grid_file = function(item) {
		return '<div class="gantt-tree-icon"><i class="far fa-fw fa-file"></i></div>';
	};
	// Plus/Minus buttons
	gantt.templates.grid_open = function(item) {
		let html = item.$open ?
			'<i class="gantt_tree_icon gantt_close far fa-fw fa-minus-square"></i>' :
			'<i class="gantt_tree_icon gantt_open far fa-fw fa-plus-square"></i>';
		html = '<div class="gantt-tree-icon">' + html + '</div>';
		return html;
	};

	// Prevent moving to another sub-branch
	gantt.attachEvent("onBeforeTaskMove", function(id, parent, tindex){
	    var task = gantt.getTask(id);
	    if (task.parent != parent)
	        return false;
	    return true;
	});

	// Auto convert task type to porject when adding subtasks
	gantt.config.auto_types = true;

	// Scales
	var weekScaleTemplate = function (date) {
		var dateToStr = gantt.date.date_to_str("%d %M");
		var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
		return dateToStr(date) + " - " + dateToStr(endDate);
	};

	// Zoom
	var zoomConfig = {
		levels: [
			{
				name:"week",
				scale_height: 50,
				min_column_width: 30,
				scales:[
					//{unit: "week", step: 1, format: weekScaleTemplate},
					{unit: "month", format: "%F, %Y"},
					{unit: "day", step: 1, format: "%j"}
				]
			},
			{
				name:"month",
				scale_height: 50,
				min_column_width: 100,
				scales:[
					{unit: "month", format: "%F, %Y"},
					{unit: "week", step: 1, format: weekScaleTemplate},
				]
			},
			{
				name:"year",
				scale_height: 50,
				scales:[
					{unit: "year", step: 1, format: "%Y"},
					{unit: "month", format: "%F, %Y"},
				]
			}
		]
	};

	gantt.ext.zoom.init(zoomConfig);
	gantt.ext.zoom.setLevel("week");

	function zoomIn() {
		gantt.ext.zoom.zoomIn();
	}
	function zoomOut() {
		gantt.ext.zoom.zoomOut()
	}

	// Inline actions
	let inlineActions = function (task) {
			let stub = '<i class="fas <class> fa-fw cursor-pointer gantt-inline-action-button" onclick="clickGridButton(\'<taskId>\', \'<action>\')"></i>';
			stub = _.replace(stub, '<taskId>', task.id);
			let edit = _.replace(stub, '<action>', 'edit');			
			edit = _.replace(edit, '<class>', 'fa-pencil-alt');
			let add = _.replace(stub, '<action>', 'add');
			// Hide add button for readonly tasks and projects
			let addClass = canEditTask(task) ? 'fa-plus' : 'fa-plus gantt-inline-action-button-disabled';
			add = _.replace(add, '<class>', addClass);
			let remove = _.replace(stub, '<action>', 'delete');
			// Hide remove button for the projects (root tasks) and readonly tasks
			let removeClass = (task.parent && canEditTask(task)) ? 'fa-times' : 'fa-times gantt-inline-action-button-disabled';
			remove = _.replace(remove, '<class>', removeClass);
			let goto = _.replace(stub, '<action>', 'goto');
			goto = _.replace(goto, '<class>', 'fa-eye');
			// Get code for all the buttons
			let wrapperStub = '<div class="gantt-inline-actions"><buttons></div>';
			let buttons = _.replace(wrapperStub, '<buttons>', goto + add + remove);
			return buttons;
		};

	function clickGridButton(id, action) {
		switch (action) {
			case "edit":
				gantt.showLightbox(id);
				break;
			case "add":
				gantt.createTask({color: defaultTaskColor}, id);
				break;
			case "delete":
				gantt.confirm({
					title: gantt.locale.labels.confirm_deleting_title,
					text: gantt.locale.labels.confirm_deleting,
					callback: function (res) {
						if (res)
							gantt.deleteTask(id);
					}
				});
				break;
			case "goto":
				break
		}
	}	

	// Inline editing
	var textEditor = {type: "text", map_to: "text"};
	var startDateEditor = {type: "date", map_to: "start_date"};
	// TODO: change to custom editor to make end_date inclusive
	var endDateEditor = {type: "date", map_to: "end_date"};
	var durationEditor = {type: "number", map_to: "duration", min: 0};
	var workEditor = {type: "number", map_to: "work", min: 0};
	var costEditor = {type: "number", map_to: "cost", min: 0};

	// Conditionally disabling inline editors
	gantt.ext.inlineEditors.attachEvent("onBeforeEditStart", function (state) {
		let task = gantt.getTask(state.id);
		// Project
		if (task.parent == 0) {
			// Disable all
			return false;
		}		
		// Summary task
		if (task.type == 'project') {
			// Allow to edit only task name
			return state.columnName == 'text';
		} else {
			// Regular task
			// Allow to edit work only if assignee is role or user
			if (state.columnName == 'work' && !canAssigneeDoWork(task.assignee)) {
				return false;
			}
			// Allow to edit cost only if assingee is empty or organization
			if (state.columnName == 'cost' && canAssigneeDoWork(task.assignee)) {
				return false;
			}
		}
		return true;
	});

	// Assignee field
	var getAssigneeInput = function (node) {
	    return node.querySelector("select");
	};
	 
	gantt.config.editor_types.assignee = {
	    show: function (id, column, config, placeholder) {	 
	 		let selectStub = '\
	        	<select name="<column>">\
	        		<emptyOption>\
	        		<optgroup value="role" label="Роли">\
	        			<roleOptions>\
	        		</optgroup>\
	        		<optgroup value="user" label="Пользователи">\
	        			<userOptions>\
	        		</optgroup>\
	        		<optgroup value="user" label="Контрагенты">\
	        			<organizationOptions>\
	        		</optgroup>\
	        	</select>\
	        ';
	        let optionStub = '<option value="<value>"><label></option>';
	        let emptyOption = '<option value="empty">Не выбран</option>';

	        var task = gantt.getTask(id);

			let filteredUsers = users;
			let filteredRoles = roles;
			let filteredOrganizations = organizations;

			// If task has been copied from the template
	        if (task.copied) {
				// Get current selection
				let selectedAssigneeId = getAssigneeId(task.assignee);
				let selectedAssigneeType = getAssigneeType(task.assignee, true);
				let selectedAssigneeRoleId = getAssigneeRoleId(task.assignee);

				// Filter users
				if (selectedAssigneeType == 'role' || selectedAssigneeType == 'user') {
					// Filter users by the same role as currently selected
					filteredUsers = _.filter(filteredUsers, function (user) {
						return user.project_role_id == selectedAssigneeRoleId;
					});
				} else {
					if (role.code == 'project_manager') {
						// Project manager can't change role/user to ogranization and vice versa
						filteredUsers = [];
					}
				}

				// Filter roles
				if (selectedAssigneeType == 'role' || selectedAssigneeType == 'user') {
					if (role.code == 'project_manager') {
						// Project manager shouldn't be able to change one role to another
						// so keep only currently selected role in the list of options
						filteredRoles = _.filter(filteredRoles, function (role) {
							return role.id == selectedAssigneeRoleId;
						});
					}
				} else {
					if (role.code == 'project_manager') {
						// Project manager can't change role/user to ogranization and vice versa
						filteredRoles = [];
					}
				}

				// Filter organizations
				if (selectedAssigneeType == 'role' || selectedAssigneeType == 'user') {
					if (role.code == 'project_manager') {
						// Project manager can't change role/user to ogranization and vice versa
						filteredOrganizations = [];
					}
				}

				// Empty option
				if (role.code == 'project_manager') {
					// Hide empty option for role/user
					if (selectedAssigneeType == 'role' || selectedAssigneeType == 'user') {
						emptyOption = '';
					}
				}
	        }

	        // Roles
	        let roleOptions = _.map(filteredRoles, function (role) {
	        	let option = _.replace(optionStub, '<value>', 'role' + role.id);
	        	option = _.replace(option, '<label>', role.name);
	        	return option;
	        }).join('');

	        // Users
	        let userOptions = _.map(filteredUsers, function (user) {
	        	let option = _.replace(optionStub, '<value>', 'user' + user.id);
	        	option = _.replace(option, '<label>', user.last_name + ' ' + user.first_name);
	        	return option;
	        }).join('');

	        // Organizations
	        let organizationOptions = _.map(filteredOrganizations, function (organization) {
	        	let option = _.replace(optionStub, '<value>', 'organization' + organization.id);
	        	option = _.replace(option, '<label>', organization.name);
	        	return option;
	        }).join('');

			let html = _.replace(selectStub, '<column>', column.name);
			html = _.replace(html, '<roleOptions>', roleOptions);
			html = _.replace(html, '<userOptions>', userOptions);
			html = _.replace(html, '<organizationOptions>', organizationOptions);
			html = _.replace(html, '<emptyOption>', emptyOption);
	        placeholder.innerHTML = html;
	    },
	    hide: function () {

	    },
	    set_value: function (value, id, column, node) {
	    	getAssigneeInput(node).value = value;
	    },
	    get_value: function (id, column, node) {
	        return getAssigneeInput(node).value;
	    },
	    is_changed: function (value, id, column, node) {
	        var currentValue = this.get_value(id, column, node);
	        return value != currentValue;
	    },
	    is_valid: function (value, id, column, node) {
	        return true;
	    },
	    focus: function (node) {

	    }
	};
	var assigneeTemplate = function (task) {
		if (task.assignee) {
			return getAssigneeLabel(task.assignee);
		}
		return "";
	}
	var assigneeEditor = {type: "assignee", map_to: "assignee"};

	// Format cost to local currency format
	var costTemplate = function (task) {
		return task.cost ? _.toNumber(task.cost).toLocaleString('ru-RU', { style: 'currency', currency: 'RUB' }) : '';
	}

	// Set dates format inside the grid
	gantt.config.date_grid = "%d.%m.%Y";

	// Get 23:59:59 instead of 00:00:00 for the end date
	var endDateTemplate = function (task) {
		return formatEndDate(task.end_date, gantt.templates.date_grid);
	}
	function formatEndDate(date, template){ 
	    return template(new Date(date.valueOf() - 1));  
	}

	// Grid fields
	gantt.config.columns = [
		{name: "text", label: "Задача", tree: true, width: 250, resize: true, editor: textEditor},
		{name: "start_date", label: "Начало", align: "center", resize: true, editor: startDateEditor, width: 140, min_width: 140},
		{name: "end_date", label: "Завершение", align: "center", resize: true, width: 140, min_width: 140, template: endDateTemplate},
		{name: "duration", label: "Длительность", align: "center", resize: true, editor: durationEditor, width: 90, min_width: 90},
		{name: "assignee", label: "Исполнитель", align: "center", resize: true, editor: assigneeEditor, template: assigneeTemplate, width: 140, min_width: 140},
		{name: "work", label: "Трудозатраты", align: "center", resize: true, editor: workEditor, width: 90, min_width: 90},
		{name: "cost", label: "Стоимость", align: "center", resize: true, editor: costEditor, template: costTemplate, width: 110, min_width: 110},

		{name: "buttons", label: "", align: "center", width: 75, min_width: 75, template: inlineActions}
	];

	// Gantt weekends styling
	gantt.templates.timeline_cell_class = function (task, date) {
		if (!gantt.isWorkTime(date))
			return "week_end";
		return "";
	};

	// Disable linking projects (root elements, not summary tasks) to other tasks and vice versa
	gantt.attachEvent("onBeforeLinkAdd", function(id, link){
        var sourceTask = gantt.getTask(link.source);
        var targetTask = gantt.getTask(link.target);
        if (sourceTask.parent == 0 || targetTask.parent == 0) {
        	return false;
        }
	});	

	// Data processor
	var dp = gantt.createDataProcessor({ 
	   task: {
	      create: function(data) {
			let parentType = _.startsWith(data.parent, 'project') ? 'App\\Project' : 'App\\ProjectTask';
			let parentId = _.replace(data.parent, 'project', '');
	      	return Eloquent.ProjectTask.create({ 
				name: data.text,
				start_date: data.start_date,
				end_date: data.end_date,
				duration: data.duration,
				parent_id: parentId,
				parent_type: parentType,
				cost: data.cost,
				work: data.work,
				assignee_id: getAssigneeId(data.assignee),
				assignee_type: getAssigneeType(data.assignee),
	      	}).then(function (task) {
	      		return {action: "inserted", tid: task.id};
	      	});
	      },
	      update: function(data, id) {
			if (data.target) {
				let siblings = gantt.getSiblings(id);
				return Eloquent.ProjectTask.reorder(siblings).then(function (response) {
					return response.json().then(function (body) {
						return {action: "updated"};
					});
				});
			}
	      	let isProject = data.parent == 0;
	      	let modelId = isProject ? _.replace(id, 'project', '') : id;
			let model = isProject ? new Eloquent.Project({id: modelId}) : 
				new Eloquent.ProjectTask({id: modelId});
			model.exists = true;
			model.name = data.text;
			model.start_date = data.start_date;
			model.end_date = data.end_date;
			model.duration = data.duration;
			model.cost = data.cost;
			if (!isProject) {
				model.work = data.work;
				model.assignee_id = getAssigneeId(data.assignee);
				model.assignee_type = getAssigneeType(data.assignee);
			}
			return model.save().then(function () {
				return {action: "updated"};
			});
	      },
	      delete: function(id) {
			let model = new Eloquent.ProjectTask({id: id});
			model.exists = true;
			return model.delete().then(function () {
				return {action: "deleted"};
			});
	      }
	   },
	   link: {
	      create: function(data) {
	      	return Eloquent.ProjectTaskLink.create({ 
				source_id: data.source,
				target_id: data.target,
				type: data.type,
	      	}).then(function (link) {
	      		return {action: "inserted", tid: link.id};
	      	});
	      },
	      update: function(data, id) {
			let model = new Eloquent.ProjectTaskLink({id: id});
			model.exists = true;
			model.source_id = data.source;
			model.target_id = data.target;
			model.type = data.type;
			return model.save().then(function () {
				return {action: "updated"};
			});
	      },
	      delete: function(id) {
			let model = new Eloquent.ProjectTaskLink({id: id});
			model.exists = true;
			return model.delete().then(function () {
				return {action: "deleted"};
			});
	      }
	   }
	});	

	// Tasks with "project" type can't track dates, so we force them to save changes to the server
	var tasksToUpdate = [];
	var previous_state = [];
	gantt.attachEvent("onBeforeLightbox", function(id) {
		previous_state = JSON.parse(JSON.stringify(gantt.serialize().data))    
		return true;
	});
	gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e) {
	    previous_state = JSON.parse(JSON.stringify(gantt.serialize().data))
	    return true;
	});
	gantt.attachEvent("onAfterTaskUpdate", function(id, item) {
		if (_.indexOf(tasksToUpdate, id) >= 0) {
			_.pullAll(tasksToUpdate, [id]);
		} else {
		    var current_state = JSON.parse(JSON.stringify(gantt.serialize().data));
		    for (var i = 0; i < previous_state.length; i++) {
		        for (var j = 0; j < current_state.length; j++) {
		            if (current_state[j].id == previous_state[i].id){
		                if (current_state[j].start_date != previous_state[i].start_date || 
		                	current_state[j].end_date != previous_state[i].end_date) {
		                	if (current_state[j].type == 'project') {
								tasksToUpdate = _.union(tasksToUpdate, [current_state[j].id]);
								gantt.updateTask(current_state[j].id);
		                	}
		                }
		            }
		        }
		    }
		}
	});

	// Add scroll to the grid
	gantt.config.layout = {
		css: "gantt_container",
		cols: [
			{
				width: 700,
				rows: [
					{view: "grid", scrollX: "gridScroll", scrollable: true, scrollY: "scrollVer"},
					{view: "scrollbar", id: "gridScroll", group: "horizontal"}
				]
			},
			{resizer: true, width: 1},
			{
				rows:[
					{view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer"},
					{view: "scrollbar", id: "scrollHor", group: "horizontal"}
				]
			},
			{view: "scrollbar", id: "scrollVer"}
		]
	};

	// Recalculate cost of summary tasks when the cost of subtasks changes
	(function dynamicProgress() {

		function calculateSummaryProgress (task) {
			if (task.type != gantt.config.types.project)
				return task.cost;
			var totalCost = 0;
			gantt.eachTask(function (child) {
				if (child.type != gantt.config.types.project) {
					totalCost += _.toNumber(child.cost);
				}
			}, task.id);
			return totalCost;
		}

		function refreshSummaryProgress (id) {
			if (!gantt.isTaskExists(id)) {
				return;
			}
			var task = gantt.getTask(id);
			var newCost = calculateSummaryProgress(task);
			if (task.cost != newCost) {
				task.cost = newCost;
				gantt.updateTask(id);
			}
		}

		gantt.attachEvent("onParse", function () {
			gantt.eachTask(function (task) {
				task.cost = calculateSummaryProgress(task);
			});
		});

		gantt.attachEvent("onAfterTaskUpdate", function (id) {
			refreshSummaryProgress(gantt.getParent(id));
		});

		gantt.attachEvent("onBeforeTaskAdd", function (id, task) {
			task.cost = 0;
			task.assignee = 'empty';
		});
		gantt.attachEvent("onAfterTaskAdd", function (id) {
			refreshSummaryProgress(gantt.getParent(id));
		});


		(function () {
			var idParentBeforeDeleteTask = 0;
			gantt.attachEvent("onBeforeTaskDelete", function (id) {
				idParentBeforeDeleteTask = gantt.getParent(id);
			});
			gantt.attachEvent("onAfterTaskDelete", function () {
				refreshSummaryProgress(idParentBeforeDeleteTask);
			});
		})();
	})();

	// Custom lightbox
	gantt.attachEvent("onBeforeLightbox", function (id) {
	    var task = gantt.getTask(id);
	    // Add new task confirmation
	    if (task.$new) {
	        gantt.confirm({
	            text: "Create task?",
	            callback: function(res){
	                if (res) {
	                	delete task.$new;
	                    gantt.addTask(task);
	                } else {
	                	gantt.deleteTask(task.id);
	                }
	            }
	        });
	        return false;
	    } else {
	    	window.open(getTaskUrl(id), '_blank');
	    }
	    return false;
	});

	function getTaskUrl(id)
	{
		let extId = id.replace(/\D/g, '');
		let task = gantt.getTask(id);
		let isRoot = task.parent == 0;
		let resource = isRoot ? 'projects' : 'project-tasks';
		return window.location.origin + '/apply/resources/' + resource + '/' + extId;
	}

	// Assignee - Work - Cost logic
	gantt.attachEvent("onBeforeTaskUpdate", function(id, newTask) {
		// Clear work and assignee fields for the summary tasks
	    if (newTask.type == gantt.config.types.project) {
	    	newTask.work = null;
	    	newTask.assignee = null;
	    } else {
	    	if (newTask.assignee == null) {
	    		newTask.assignee = 'empty';
	    	}
	    }
	});
	gantt.attachEvent("onParse", function () {
		gantt.eachTask(function (task) {
			// Clear work and assignee fields for the summary tasks
		    if (task.type == gantt.config.types.project || task.parent == 0) {
		    	task.work = null;
		    	task.assignee = null;
		    }
		});
	});
 
	gantt.ext.inlineEditors.attachEvent("onBeforeSave", function (state) {
		let task = gantt.getTask(state.id);
		switch (state.columnName) {
			case "assignee":			
	    		if (!canAssigneeDoWork(state.newValue)) {
	    			task.work = null;
	    		} else {
	    			if (task.work == null) {
						task.work = 0;
						task.cost = 0;
	    			} else {
	    				// Calculate new cost
						let rate = getAssigneeRateForTask(state.newValue, state.id);
						task.cost = task.work * rate;
	    			}
	    		}
				break;
			case "work":
				// Calculate new cost
				let rate = getAssigneeRateForTask(task.assignee, task.id);
				task.cost = state.newValue * rate;
				break;
			case "cost":
				break;
		}
	});

	// Recalculate costs on open
	function recalculateCosts() {
		gantt.eachTask(function (task) {
			// Only for tasks
		    if (task.type != gantt.config.types.project && !task.readonly) {
		    	// Only for tasks with assignee
		    	if (canAssigneeDoWork(task.assignee)) {
		    		// Get assignee rate
		    		let rate = getAssigneeRateForTask(task.assignee, task.id);
		    		// Calculate new cost
		    		let newCost = task.work ? task.work * rate : 0;
		    		// If cost has been changed
		    		if (task.cost != newCost) {
		    			task.cost = newCost;
		    			gantt.updateTask(task.id);
		    		}
		    	}
		    }
		});		
	}

	function getAssigneeLabel(id)
	{
		if (id == 'empty') {
			return 'Не выбран';
		}
		let isRole = _.startsWith(id, 'role');
		let isUser = _.startsWith(id, 'user');
		let isOrganization = !isRole && !isUser;
		let list = isRole ? roles : (isUser ? users : organizations);
		let extId = id.replace(/\D/g, '');
		let assignee = _.find(list, function (item) {
			return item.id == extId;
		});
		return isUser ? assignee.last_name + ' ' + assignee.first_name : assignee.name;
	}

	function getAssigneeType(id, internal = false)
	{
		if (id == undefined || id == 'empty') {
			return null;
		}
		let extId = id.replace(/\D/g, '');
		let type = _.replace(id, extId, '');
		if (internal) {
			return type;
		}
		return _.findKey(assigneeTypeMap, function (item) {
			return item == type;
		});
	}

	function getAssigneeId(id)
	{
		if (id == undefined || id == 'empty') {
			return null;
		}
		return id.replace(/\D/g, '');
	}

	function canAssigneeDoWork(id)
	{
		let assigneeType = getAssigneeType(id);
		if (assigneeType) {
			assigneeType = assigneeTypeMap[assigneeType];
		}
		if (assigneeType == null || assigneeType == 'organization') {
			return false;
		}
		return true;
	}

	function getAssigneeRoleId(id)
	{
		let type = getAssigneeType(id, true);
		let extId = getAssigneeId(id);
		if (type == 'user') {
		 	let user = _.find(users, function (user) {
		 		return user.id == extId;
		 	});
		 	return user.project_role_id;
		}
		if (type == 'role') {
			return extId;
		}
		return null;
	}

	function getAssigneeRateForTask(assigneeId, taskId)
	{
		// Get project
		let task = gantt.getTask(taskId);
		let parents = [];
		gantt.eachParent(function (parent) {
			parents.push(parent.id);
		}, taskId);
		let projectId = parents.reverse()[0];
		let projectExtId = _.replace(projectId, 'project', '');
		// Get assignee role
		let roleIdExt = getAssigneeRoleId(assigneeId);
		if (roleIdExt == null) {
			return 0;
		}
		// Get project rate for the assignee
		let rate = _.find(rates, function (rate) {
			return rate.project_id == projectExtId && rate.project_role_id == roleIdExt;
		});
		if (rate) {
			return rate.hourly_rate;
		} else {
			// If not specified, get rate from role itself
			let role = _.find(roles, function (role) {
				return role.id == roleIdExt;
			});
			if (role) {
				return role.hourly_rate;
			}
		}
		return 0;
	}

	var assigneeTypeMap = {
		'App\\ProjectRole': 'role',
		'App\\User': 'user',
		'App\\Organization': 'organization'
	}

	// Access rights for projects and tasks	
	function canEditProject(project) {
		if (role.code == 'project_manager') {
			let projectStates = ['draft', 'revision', 'in_progress'];
			if (projectStates.indexOf(project.state_code) != -1) {
				return true;
			}
		}
		if (role.code == 'administrator') {
			return true;
		}
		if (role.code == 'finance' || role.code == 'commerce') {
			return false;
		}
		return false;
	}

	function canEditTask(task) {
		let project = getTaskRoot(task.id);
		return canEditProject(project);
	}

	function canEditLink(link) {
		let project = getTaskRoot(link.source);
		return canEditProject(project);
	}

	gantt.attachEvent("onParse", function () {
		gantt.eachTask(function (task) {
			task.readonly = !canEditTask(task);
		});
		var links = gantt.getLinks();
		_.forEach(links, function (link) {
			link.readonly = !canEditLink(link);
		});
	});

	// Baselines
	// Convert planned dates to gantt format
	gantt.attachEvent("onTaskLoading", function(task){
	    task.planned_start = gantt.date.parseDate(task.planned_start, "%Y-%m-%d");
	    task.planned_end = gantt.date.parseDate(task.planned_end, "%Y-%m-%d");
	    return true;
	});

	// Render baselines
	gantt.addTaskLayer({
		renderer: {
			render: function draw_planned(task) {
				if (task.planned_start && task.planned_end) {
					var sizes = gantt.getTaskPosition(task, task.planned_start, task.planned_end);
					var el = document.createElement('div');
					el.className = 'baseline';
					el.style.left = sizes.left + 'px';
					el.style.width = sizes.width + 'px';
					el.style.top = sizes.top + gantt.config.bar_height + 13 + 'px';
					return el;
				}
				return false;
			},
			// define getRectangle in order to hook layer with the smart rendering
			getRectangle: function(task, view){
				if (task.planned_start && task.planned_end) {
					return gantt.getTaskPosition(task, task.planned_start, task.planned_end);
				}
				return null;
			}
		}
	});	

	function isLinkBaseline(link) {
		let task = _.find(tasks, ['id', link.source_id]);
		let project = _getTaskRoot(task);
		return project.type == 'baseline';
	}

	function isTaskBaseline(task) {
		let project = _getTaskRoot(task);
		return project.type == 'baseline';
	}

	function _getTaskRoot(task)
	{
		if (_.endsWith(task.parent_type, 'ProjectTask')) {
			let parentTask = _.find(tasks, ['id', task.parent_id]);
			return _getTaskRoot(parentTask)
		} else {
			return _.find(projects, ['id', task.parent_id]);
		}
	}

	// Load data from the server
	var user, projects, tasks, links, roles, users, organizations, rates, customers, baselines, baselineTasks, baselineLinks;
	async function main() {
		
console.log('2');

		// Projects, tasks, links
		let projectRequest = Eloquent.Project;
		let taskRequest = Eloquent.ProjectTask;
		let linkRequest = Eloquent.ProjectTaskLink;

		//projectRequest = projectId ? projectRequest.where('id', projectId) : projectRequest;
		//taskRequest = projectId ? taskRequest.ofProject(projectId) : taskRequest;
		//linkRequest = projectId ? linkRequest.ofProject(projectId) : linkRequest;
		if (role.code == 'administrator') {
			projectRequest = projectRequest.templates();
			taskRequest = taskRequest.ofTemplates();
			linkRequest = linkRequest.ofTemplates();
		} else if (role.code == 'project_manager') {
			projectRequest = projectRequest.ofType('project|baseline').my();
			taskRequest = taskRequest.ofProjectType('project|baseline').my();
			linkRequest = linkRequest.ofProjectType('project|baseline').my();
		} else if (role.code == 'finance' || role.code == 'commerce') {
			let projectStates = 'approved|in_progress|revision|customer_approval|commerce_approval|finance_approval|archive';
			projectRequest = projectRequest.ofType('project|baseline').ofState(projectStates);
			taskRequest = taskRequest.ofProjectType('project|baseline').ofProjectState(projectStates);
			linkRequest = linkRequest.ofProjectType('project|baseline').ofProjectState(projectStates);
		}
		projectRequest = projectRequest.get();
		taskRequest = taskRequest.ordered().get();
		linkRequest = linkRequest.get();

console.log('3');

		[user, projects, tasks, links, roles, users, organizations, rates] = await Promise.all([
			Eloquent.User.me().first(),
			projectRequest,
			taskRequest,
			linkRequest,
			Eloquent.ProjectRole.get(),
			Eloquent.User.get(),
			Eloquent.Organization.get(),
			Eloquent.ProjectRate.get()
		]);

console.log('4');

console.log(projects);

		// If user requested to specifically open baseline
		if (projectId) {
			let project = _.find(projects, function (project) {
				return project.id == projectId;
			});
			if (project) {
				project.type = 'project';
			}
		}

		// Get baselines and baseline tasks as separate lists
		// Separate baselines and leave only one baseline per project (latest approved)
		let approvedBaselines = _.filter(projects, function (baseline) {
			return baseline.type == 'baseline' && baseline.state_code == 'approved';
		});
		approvedBaselines = _.groupBy(approvedBaselines, 'project_id');
		approvedBaselines = _.flatMap(approvedBaselines, function (projectBaselines) {
			return _.last(_.sortBy(projectBaselines, ['state_timestamp']));
		});
		baselines = approvedBaselines;

		// Delete tasks and links of filtered baselines
		let baselineIds = _.map(baselines, function (baseline) {
			return baseline.id;
		});
		links = _.filter(links, function (link) {
			let task = _.find(tasks, ['id', link.source_id]);			
			let project = _getTaskRoot(task);		
			return _.indexOf(baselineIds, project.id) != -1 || !isLinkBaseline(link);
		});
		tasks = _.filter(tasks, function (task) {
			let project = _getTaskRoot(task);		
			return _.indexOf(baselineIds, project.id) != -1 || !isTaskBaseline(task);
		});

		// Separate links
		baselineLinks = _.filter(links, function (link) {
			return isLinkBaseline(link);
		});
		links = _.filter(links, function (link) {
			return !isLinkBaseline(link);
		});
		// Separate tasks
		baselineTasks = _.filter(tasks, function (task) {
			return isTaskBaseline(task);
		});
		tasks = _.filter(tasks, function (task) {
			return !isTaskBaseline(task);
		});
		// Separate projects
		projects = _.filter(projects, function (project) {
			return project.type == 'project';
		});

		// Filter by selected project
		if (projectId) {
			links = _.filter(links, function (link) {
				let task = _.find(tasks, ['id', link.source_id]);		
				let project = _getTaskRoot(task);
				return project.id == projectId;
			});
			tasks = _.filter(tasks, function (task) {				
				let project = _getTaskRoot(task);
				return project.id == projectId;
			});
			projects = _.filter(projects, function (project) {
				return project.id == projectId;
			});
		}

		// Separate customers
		customers = _.filter(organizations, function (organization) {
			return organization.is_customer;
		});
		organizations = _.filter(organizations, function (organization) {
			return organization.is_contractor;
		});

		// Load resources
		if (role.code == 'administrator') {
			users = [];
			organizations = [];
		}

		// Convert projects go gantt format
		projects = _.map(projects, function (project) {
			let baseline = _.find(baselines, ['project_id', project.id]);
			return {
				id: 'project' + project.id,
				text: project.name,
				start_date: project.start_date,
				end_date: project.end_date,
				duration: project.duration,
				type: 'project',
				open: true,
				cost: project.cost,
				color: defaultSummaryColor,
				customer: project.customer_id,
				state_code: project.state_code,
				planned_start: baseline ? baseline.start_date : null,
				planned_end: baseline ? baseline.end_date : null,
			};
		});

		// Convert tasks go gantt format
		tasks = _.map(tasks, function (task) {
			let hasChilds = _.filter(tasks, function (item) {
				return item.parent_id == task.id && !_.endsWith(item.parent_type, 'Project');
			}).length;
			let baselineTask = _.find(baselineTasks, ['project_task_id', task.id]);

			return {
				id: task.id,
				text: task.name,
				start_date: task.start_date,
				end_date: task.end_date,
				duration: task.duration,
				parent: _.endsWith(task.parent_type, 'Project') ? 'project' + task.parent_id : task.parent_id,
				type: hasChilds ? 'project' : 'task',
				open: true,
				cost: task.cost,
				work: task.work,
				assignee: task.assignee_id ? assigneeTypeMap[task.assignee_type] + task.assignee_id : 'empty',
				color: defaultTaskColor,
				copied: task.copied,
				planned_start: baselineTask ? baselineTask.start_date : null,
				planned_end: baselineTask ? baselineTask.end_date : null,
			};
		});

		// Merge projects with tasks
		tasks = _.concat(projects, tasks);
		// Convert links go gantt format
		links = _.map(links, function (link) {
			return {
				id: link.id,
				source: link.source_id,
				target: link.target_id,
				type: link.type,
				//readonly: true,
			};
		});

		// Prepare data to be parsed in gantt
		let data = {
			tasks: tasks,
			links: links
		};

		// Load data to gantt
		gantt.init("gantt_here");		
		gantt.parse(data);

		// Init data processor
		dp.init(gantt);

		// Recalculate costs
		recalculateCosts();

	}
	main();

</script>

</body>
</html>
