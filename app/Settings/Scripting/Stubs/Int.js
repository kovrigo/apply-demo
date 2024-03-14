global.token = context.user.api_token; 

var _ = require('lodash'); 
var Eloquent = require('eloquentjs');
var FormData = require('form-data');
var fs = require('fs');
var axios = require('axios');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';