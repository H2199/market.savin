//form handlers
function showOrderForm() {
	$("#order_form").css({"display":"block"});
	$("#order_form_bg").css({"display":"block"});
}
function hideOrderForm() {
	$("#order_form").css({"display":"none"});
	$("#order_form_bg").css({"display":"none"});
}
function showAnswer() {
	$("#order_answer").css({"display":"block"});
	$("#order_form_bg").css({"display":"block"});
}
function hideAnswer() {
	$("#order_answer").css({"display":"none"});
	$("#order_form_bg").css({"display":"none"});
}
function showError() {
	$("#order_error").css({"display":"block"});
	$("#order_form_bg").css({"display":"block"});
}
function hideError() {
	$("#order_error").css({"display":"none"});
	$("#order_form_bg").css({"display":"none"});
}
function makeOrder() {
//customer's info check
	if ($("#form_name").val() == "")
	{
		alert("I would ask you to mention name. I wish to know what s your name :).");
		return false;
	}
	if ($("#form_email").val() == "")
	{
		alert("I would ask you to mention email. It is really needed for me to answer you.");
		return false;
	}
if ($("#form_link").val() ==  "" && $("#form_about").val() == "")
	{
		alert("Place the link or write the message. Otherwise how can I know the purpose of your letter?");
		return false;
	}
	//alert("ok");
	return true;
	yaCounter37608890.reachGoal('order');
}
  window.onload = function()
{
	var search = window.location.search;
	if(search.includes("?buy"))
	{
		showOrderForm();
	}
	if(search.includes("?sent=1"))
	{
		setTimeout(showAnswer, 500);
		setTimeout(hideAnswer, 5000);
	}
	if(search.includes("?error=1"))
	{
		setTimeout(showError, 500);
		setTimeout(hideError, 5000);
	}
}