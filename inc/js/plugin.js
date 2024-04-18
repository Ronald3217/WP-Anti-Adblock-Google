/*
* Get the plugin parameters into a variable: myPluginParams
*
* @since 1.0.0
*
* @param array $myPluginParams
*/

const ScriptElement = document.querySelector("#wpaag_script-js");
ScriptElement.setAttribute("nonce", myPluginParams.wpaag_nonce_key);
const ScriptElement2 = document.querySelector("#wpaag_script-js-after");
ScriptElement2.setAttribute("nonce", myPluginParams.wpaag_nonce_key);