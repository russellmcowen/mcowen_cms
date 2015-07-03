$(document).ready(function() {
	$("#login_button").bind('click', function() {
		var login_name = $("#login_name").val();
		var login_pass = $("#login_pass").val();
		var remember = $("#remember").is(':checked');
		if((login_name === null || login_pass === null) || (login_name === "" || login_pass === "")) {
			showMessage('Please Enter Both Username and Password');
		} else { 
			if (remember) {
				ajaxLogin(login_name, login_pass, 'on');
			} else {
				ajaxLogin(login_name, login_pass);
			}
		}
	});
	$("#login").bind('keypress', function(e) {
		var code = e.keyCode || e.which;
		if(code == 13) { $("#login_button").click(); }
	});
	
	$("#reg_button").bind('click', function() {
		var reg_name = $("#reg_name").val();
		var reg_email = $("#reg_email").val();
		var reg_pass = $("#reg_pass").val();
		var reg_pass_conf = $("#reg_pass_conf").val();
		ajaxRegister(reg_name, reg_email, reg_pass, reg_pass_conf);
	});

	
	$("#search_button").bind('click', function() {
		if ($("#search_text").val().length > 0) {
			var loc = '/catalog/search/' + $("#search_text").val();
			var cat = $("#search_category").val();
			if (cat != '') {
				loc += '/'+cat;
			}
			ajaxBrowse(loc);
		} else {
			showMessage('Search Input Empty');
		}
		
	});
	
	$("#catalog_search").bind('keypress', function(e) {
		var code = e.keyCode || e.which;
		if(code == 13) {
			e.preventDefault();
			$("#search_button").click();
		}
		
	});
		
	$("#back").bind('click', function() {
		//hideDisplay();
	});

 	$("#nojava").css("display", "none");
 	
 	$("#change_email_button").bind('click', function() {
 		showMessage('test');
 		//changeEmail($("#change_email_text").val());
	});
 	/*
	$("#change_pass_button").bind('click', function() {
		changePass($("#change_pass_text").val());
	});
	
	*/
});

function showDisplay() {
	$("#back").fadeIn();
	$("#show").fadeIn();
}
function hideDisplay() {
	$("#show").fadeOut();
	$("#back").fadeOut();
}

function showMessage(s) {
	$("#message").html(s);
	$("#message").fadeIn().delay(3700).fadeOut();
}

function ajaxLogin(name, pass, r) {
	var data = {};
	if (r) { data = {'login_name': name, 'login_pass': pass, 'remember': r}; } 
	else { data = {'login_name': name, 'login_pass': pass}; }
	$.post("/ajax", data, function(d) {
		if(d != 'refresh') { showMessage(d); }
		else { location.reload(); }
	});
}

function ajaxRegister(name, email, pass, pass2) {
	if(name.length > 12 || name.length < 4) {
		showMessage('Username Must be Between 4 and 12 Characters');
		return;
	}
	if(pass.length > 24 || pass.length < 8) {
		showMessage('Password Must be Between 8 and 24 Characters');
		return;
	}
	if(pass !== pass2) {
		showmessage('Password Confirmation Did Not Match');
		return;
	}
	var data = {'reg_name': name, 'reg_email': email, 'reg_pass': pass};
	
	$.post("/ajax", data, function(d) {
		if(d != 'refresh') { 
			showMessage(d); 
		} else { 
			document.getElementById("register").reset(); 
			showMessage('Account Successfully Registered<br />You Can Now Log In'); 
		}
	});
}

function ajaxBrowse(c) {
	window.location.replace(c);
}

function ajaxPurchaseShow(html) {
	$("#back").fadeIn();
	$("#show").fadeIn();
}

function ajaxPurchase(item) {
	$.post("/ajax", {purchase:item}, function(d) {
		if(d === 'refresh') {
			$("#show").html('Your Purchase Was Successful.  Your Code Will Be Sent to Your Email Address in 24 to 48 Hours.<br />Page Will <a href="/">Refresh</a> in a Few Seconds.');
			setTimeout(function() {
				window.location.replace('/');
			}, 3500);
		}
	});
}

function changeEmail(newEmail) {
	showMessage('test');
	/*
	$.post("/ajax", {newEmail:newEmail}, function(d) {
		if (d === 'refresh') {
			location.reload();
		} else {
			showMessage(d);
		}
	});
	*/
}

function changePass(newPass) {
	$.post("/ajax", {newPass:newPass}, function(d) {
		if (d === 'refresh') {
			location.reload();
		} else {
			showMessage(d);
		}
	});
}

function test() {
	location.reload();
}
