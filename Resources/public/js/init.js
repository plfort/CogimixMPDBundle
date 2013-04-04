$(document).ready(function(){
	var $modal = $("#manageMPDModal");
	var $modalBody = $("#manageMPDModal .modal-body");
	$("#manageMPDBtn").click(function(event){
		$.get(Routing.generate('_mpd_manage_modal'),function(response){
			if(response.success==true){
				$modalBody.html(response.data.modalContent);
				$modal.modal('toggle');
			}
		},'json');
		
	});
	
	
	$modal.on('click','.mpdServerInfoItem',function(event){
		$modal.find('li').removeClass('active');
		$(this).closest('li').addClass('active');
		$.get(Routing.generate('_mpd_edit',{'id':$(this).data('id')}),function(response){
			if(response.success==true){
				var formContainer=$modalBody.find('div#mpdServerInfoForm')
				formContainer.html(response.data.formHtml);
				//$("#manageCustomProviderModal").modal('toggle');
			}
		},'json');
	});
	
	$modalBody.on('click','#createMPDServerInfoBtn',function(event){
		$.get(Routing.generate('_mpd_create'),function(response){
			if(response.success==true){
				var formContainer=$modalBody.find('div#mpdServerInfoForm')
				formContainer.html(response.data.formHtml);
				//$("#manageCustomProviderModal").modal('toggle');
			}
		},'json');
		
		return false;
		
	});
	$modalBody.on('submit','form',function(evnet){
		var formData = $(this).serialize();
		$.post($(this).attr('action'),formData,function(response){
			if(response.success==true){
				$modalBody.find('div#mpdServerInfoForm').before('<div class="span3 center alert alert-success">Saved !</div>')

				if(response.data.formType=='create'){
					$modalBody.find("#mpdServerInfoList").append(response.data.newItem);
				    
				}
				//$("#manageCustomProviderModal").modal('toggle');
			}else{
				var formContainer=$modalBody.find('div#mpdServerInfoList')
				formContainer.html(response.data.formHtml);
			}
		},'json');
		console.log('submit form mpd server')
		return false;
	});
});