var App = {
	vr : {
		user : {
			turn : 1 //1 - user, -1 - ai
		},
		app : {
			cross : '<span class="glyphicon glyphicon-remove"></span>',
			zero : '<span class="glyphicon glyphicon-record"></span>',
			run : true,
			start : 0,
			finish: 0
		}
	},
	init : function(){
		App.page.drawField();	
		$('.cell').on('click', function(){
			if(App.vr.user.turn == -1) {
				App.page.error('Ai turn to move.')
				return false;			
			}
			cell = $(this)
			i = cell.data('i')
			j = cell.data('j')
			App.page.clickCell(i, j);
		})	
	},
	page : {
		drawField : function(){
			block = $('.field');
			block.empty();
			//i - stroki
			//j - slobcy
			for(var i=0; i<20; i++) {
				for(var j=0; j<20; j++) {
					cell = $('<div>')
						.addClass('cell' + ' row'+i + ' col'+j)
						.data({'i': i, 'j': j})
					block.append(cell)
				}
			}
		},
		
		clickCell : function(i, j){
			if(App.vr.app.run === false) return false;
			cell = $('.row'+i +'.col'+j)
			if(cell.hasClass("active")) {
				App.page.error('Already used.')
				return false;
			}
			if(App.vr.app.start == 0) {
				App.vr.app.start = new Date().getTime()/1000;
			}
			
			draw = (App.vr.user.turn == 1) ? App.vr.app.cross : App.vr.app.zero;
			cell
				.html(draw)
				.addClass('active')
				.data('type', App.vr.user.turn)
			
			//change Turn
			App.vr.user.turn *= -1;
			App.data.checkState();
			if(App.vr.user.turn == -1) {
				App.data.AImove();
			}
		},
		
		showRecordsForm : function(){
			modal = $('.modal')
			time = parseInt(App.vr.app.finish - App.vr.app.start)
			modal.find('.time').html(time)
			$('.modal').modal('show');
		},
		
		error : function(text) {
			$('.error-block').html(text);
		}
	},
	fn : {		
		collectTurns : function(){
			var data = []
			$('.cell').each(function(index, element) {
        cell = $(this)
				i = cell.data('i')
				j = cell.data('j')
				type = cell.data('type')
				if(!type) type = 0;
				if(!data[i]) data[i] = [];
				data[i][j] = type;
      });
			
			return data;
		}
	},
	data : {
		checkState : function(){
			data = App.fn.collectTurns();
			$.post('/ajax', {action: 'check_state', data: {data: data}}, function(data){
				if(data.error)
					App.page.error(data.error);
					
				if(data.status != 'fair') {
					App.vr.app.run = false;
					App.vr.app.finish = new Date().getTime()/1000;					
					App.page.showRecordsForm();
				}
			}, 'json').error(function(jqXHR, textStatus, errorThrown){
				App.page.error('Error getting data. Server responsed with message: "' + errorThrown +'"');
			})
		},
		
		AImove : function(){
			data = App.fn.collectTurns();
			$.post('/ajax', {action: 'ai_move', data: {data: data}}, function(data){				
				if(data) {
					App.page.clickCell(data[0], data[1]);
				}
				
			}, 'json').error(function(jqXHR, textStatus, errorThrown){
					App.page.error('Error getting data. Server responsed with message: "' + errorThrown +'"');
			})
		},
		
		saveRecord : function(){
			modal = $('.modal')
			name = modal.find('.name').val();
			time = modal.find('.time').text();
			
			if(!name) {
				App.page.error('Write your name please');
				return false;
			}
			$.post('/ajax', {action: 'save_record', data: {name: name, time: time}}, function(data){				
				window.location.reload();				
			}, 'json').error(function(jqXHR, textStatus, errorThrown){
					App.page.error('Error getting data. Server responsed with message: "' + errorThrown +'"');
			})
			
		}
	}
}