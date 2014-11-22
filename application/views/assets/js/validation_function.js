	function clickk(){
		alert('isus');
	}
	function validateUsername(word){
		var wordReg = new RegExp(/^[a-zA-Z0-9_\.]{1,20}$/);
		var valid = wordReg.test(word);
		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validatePassword(word){
		var wordReg = new RegExp(/^[a-zA-Z0-9]{1,20}$/);
		var valid = wordReg.test(word);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateName(word){
		var wordReg = new RegExp(/^[a-zA-Z]+$/);
		var valid = wordReg.test(word);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateAddress(word){
		var wordReg = new RegExp(/^[a-zA-Z0-9\s\.\/]+$/);
		var valid = wordReg.test(word);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateEmail(email){
		var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var valid = emailReg.test(email);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateTelephone(word){
		var wordReg = new RegExp(/^\+[0-9]{9}$/);
		var valid = wordReg.test(word);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateKeyword(word){
		var searchReg = new RegExp(/^[a-zA-Z0-9]+$/);
		var valid = searchReg.test(search);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateReceiverName(word){
		var searchReg = new RegExp(/^[a-zA-Z0-9_\.-]{1,20}$/);
		var valid = searchReg.test(search);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateSubject(word){
		if(!(word.length <=50)) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateMessageText(word){
		if(!(word.length <=200)) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	
	function validateVisaID(word){
		var searchReg = new RegExp(/^[0-9]{16}$/);
		var valid = searchReg.test(search);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateExpiredDate(word){
		var searchReg = new RegExp(/^([0-2][0-9]|30|31)\/(0[0-9]|1[0-2])\/25[0-9][0-9]$/);
		var valid = searchReg.test(search);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateProductName(word){
		if(!(word.length <=30)) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateProductDetail(word){
		if(!(word.length <=250)) {
	        return false;
	    } else {
	    	return true;
	    }
	}
	function validateDigit(word){
		var searchReg = new RegExp(/[[:digit:]]/);
		var valid = searchReg.test(search);

		if(!valid) {
	        return false;
	    } else {
	    	return true;
	    }
	}