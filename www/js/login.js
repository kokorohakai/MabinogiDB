Core.modules["login"] = function( parent ){
	this.parent = parent;
	
	var self = this;
	var callbacks = {
		login:{
			login:function(response, status, xhr){
				self.parent.checkErrors(response);
				if ( !response.errors && response.success ){
					location.href="/login";
				}
			}
		}
	}
	var events = {
		login:{
			click:function(e){
				e.preventDefault();
				var username = $("#login_username").val();
				var password = CryptoJS.SHA512( $("#login_password").val() ).toString();
				$.ajax({
					url: "/api",
					method:"post",
					dataType:"json",
					data:{
						m:"login",
						a:"login",
						username:username,
						password:password					
					},
					success: callbacks.login.login
				});
				return false;
			}
		}
	};
	(function Constructor(){
		$("#login_button").on("click", events.login.click );
	})();
}