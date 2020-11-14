// JavaScript Document

var assert = require('chai').assert;	//Chai assertion library
const validateZipCode = require('../app/validateZipCode.js');
var validInput = require('../app/validateZipCode');


// begin testing valid zip
describe("Testing Valid Zip code", function(){

    it("Empty or '' should fail", function() {
		assert.isFalse(validateZipCode(''));
    });	

    it("12345 should succeed", function() {
		assert.isTrue(validateZipCode('12345'));
    });	

    it("12345-6789 should succeed", function() {
		assert.isTrue(validateZipCode('12345-6789'));
    });	

    it("12345-5 should fail", function() {
		assert.isFalse(validateZipCode('12345-5'));
    });	

    it("12345-5 should fail", function() {
		assert.isFalse(validateZipCode('12345-5'));
    });	

    it("null should fail", function() {
		assert.isFalse(validateZipCode('null'));
    });	

    it("undefined should fail", function() {
		assert.isFalse(validateZipCode('undefined'));
    });	

    it("1234-12345 should fail", function() {
		assert.isFalse(validateZipCode('1234-12345'));
    });	

})