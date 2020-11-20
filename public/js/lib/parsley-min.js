!(function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? (module.exports = e(require("jquery"))) : "function" == typeof define && define.amd ? define(["jquery"], e) : ((t = t || self).parsley = e(t.jQuery));
})(this, function (h) {
    "use strict";
    function n(t) {
        return (n =
            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                ? function (t) {
                      return typeof t;
                  }
                : function (t) {
                      return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t;
                  })(t);
    }
    function l() {
        return (l =
            Object.assign ||
            function (t) {
                for (var e = 1; e < arguments.length; e++) {
                    var i = arguments[e];
                    for (var r in i) Object.prototype.hasOwnProperty.call(i, r) && (t[r] = i[r]);
                }
                return t;
            }).apply(this, arguments);
    }
    function o(t, e) {
        return (
            (function (t) {
                if (Array.isArray(t)) return t;
            })(t) ||
            (function (t, e) {
                if (!(Symbol.iterator in Object(t) || "[object Arguments]" === Object.prototype.toString.call(t))) return;
                var i = [],
                    r = !0,
                    n = !1,
                    s = void 0;
                try {
                    for (var a, o = t[Symbol.iterator](); !(r = (a = o.next()).done) && (i.push(a.value), !e || i.length !== e); r = !0);
                } catch (t) {
                    (n = !0), (s = t);
                } finally {
                    try {
                        r || null == o.return || o.return();
                    } finally {
                        if (n) throw s;
                    }
                }
                return i;
            })(t, e) ||
            (function () {
                throw new TypeError("Invalid attempt to destructure non-iterable instance");
            })()
        );
    }
    function u(t) {
        return (
            (function (t) {
                if (Array.isArray(t)) {
                    for (var e = 0, i = new Array(t.length); e < t.length; e++) i[e] = t[e];
                    return i;
                }
            })(t) ||
            (function (t) {
                if (Symbol.iterator in Object(t) || "[object Arguments]" === Object.prototype.toString.call(t)) return Array.from(t);
            })(t) ||
            (function () {
                throw new TypeError("Invalid attempt to spread non-iterable instance");
            })()
        );
    }
    var t = 1,
        e = {},
        d = {
            attr: function (t, e, i) {
                var r,
                    n,
                    s,
                    a = new RegExp("^" + e, "i");
                if (void 0 === i) i = {};
                else for (r in i) i.hasOwnProperty(r) && delete i[r];
                if (!t) return i;
                for (r = (s = t.attributes).length; r--; ) (n = s[r]) && n.specified && a.test(n.name) && (i[this.camelize(n.name.slice(e.length))] = this.deserializeValue(n.value));
                return i;
            },
            checkAttr: function (t, e, i) {
                return t.hasAttribute(e + i);
            },
            setAttr: function (t, e, i, r) {
                t.setAttribute(this.dasherize(e + i), String(r));
            },
            getType: function (t) {
                return t.getAttribute("type") || "text";
            },
            generateID: function () {
                return "" + t++;
            },
            deserializeValue: function (e) {
                var t;
                try {
                    return e ? "true" == e || ("false" != e && ("null" == e ? null : isNaN((t = Number(e))) ? (/^[\[\{]/.test(e) ? JSON.parse(e) : e) : t)) : e;
                } catch (t) {
                    return e;
                }
            },
            camelize: function (t) {
                return t.replace(/-+(.)?/g, function (t, e) {
                    return e ? e.toUpperCase() : "";
                });
            },
            dasherize: function (t) {
                return t
                    .replace(/::/g, "/")
                    .replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2")
                    .replace(/([a-z\d])([A-Z])/g, "$1_$2")
                    .replace(/_/g, "-")
                    .toLowerCase();
            },
            warn: function () {
                var t;
                window.console && "function" == typeof window.console.warn && (t = window.console).warn.apply(t, arguments);
            },
            warnOnce: function (t) {
                e[t] || ((e[t] = !0), this.warn.apply(this, arguments));
            },
            _resetWarnings: function () {
                e = {};
            },
            trimString: function (t) {
                return t.replace(/^\s+|\s+$/g, "");
            },
            parse: {
                date: function (t) {
                    var e = t.match(/^(\d{4,})-(\d\d)-(\d\d)$/);
                    if (!e) return null;
                    var i = o(
                            e.map(function (t) {
                                return parseInt(t, 10);
                            }),
                            4
                        ),
                        r = (i[0], i[1]),
                        n = i[2],
                        s = i[3],
                        a = new Date(r, n - 1, s);
                    return a.getFullYear() !== r || a.getMonth() + 1 !== n || a.getDate() !== s ? null : a;
                },
                string: function (t) {
                    return t;
                },
                integer: function (t) {
                    return isNaN(t) ? null : parseInt(t, 10);
                },
                number: function (t) {
                    if (isNaN(t)) throw null;
                    return parseFloat(t);
                },
                boolean: function (t) {
                    return !/^\s*false\s*$/i.test(t);
                },
                object: function (t) {
                    return d.deserializeValue(t);
                },
                regexp: function (t) {
                    var e = "";
                    return (t = /^\/.*\/(?:[gimy]*)$/.test(t) ? ((e = t.replace(/.*\/([gimy]*)$/, "$1")), t.replace(new RegExp("^/(.*?)/" + e + "$"), "$1")) : "^" + t + "$"), new RegExp(t, e);
                },
            },
            parseRequirement: function (t, e) {
                var i = this.parse[t || "string"];
                if (!i) throw 'Unknown requirement specification: "' + t + '"';
                var r = i(e);
                if (null === r) throw "Requirement is not a ".concat(t, ': "').concat(e, '"');
                return r;
            },
            namespaceEvents: function (t, e) {
                return (t = this.trimString(t || "").split(/\s+/))[0]
                    ? h
                          .map(t, function (t) {
                              return "".concat(t, ".").concat(e);
                          })
                          .join(" ")
                    : "";
            },
            difference: function (t, i) {
                var r = [];
                return (
                    h.each(t, function (t, e) {
                        -1 == i.indexOf(e) && r.push(e);
                    }),
                    r
                );
            },
            all: function (t) {
                return h.when.apply(h, u(t).concat([42, 42]));
            },
            objectCreate:
                Object.create ||
                function (t) {
                    if (1 < arguments.length) throw Error("Second argument not supported");
                    if ("object" != n(t)) throw TypeError("Argument must be an object");
                    i.prototype = t;
                    var e = new i();
                    return (i.prototype = null), e;
                },
            _SubmitSelector: 'input[type="submit"], button:submit',
        };
    function i() {}
    function r() {
        this.__id__ = d.generateID();
    }
    var s = {
        namespace: "data-parsley-",
        inputs: "input, textarea, select",
        excluded: "input[type=button], input[type=submit], input[type=reset], input[type=hidden]",
        priorityEnabled: !0,
        multiple: null,
        group: null,
        uiEnabled: !0,
        validationThreshold: 3,
        focus: "first",
        trigger: !1,
        triggerAfterFailure: "input",
        errorClass: "parsley-error",
        successClass: "parsley-success",
        classHandler: function () {},
        errorsContainer: function () {},
        errorsWrapper: '<ul class="parsley-errors-list"></ul>',
        errorTemplate: "<li></li>",
    };
    r.prototype = {
        asyncSupport: !0,
        _pipeAccordingToValidationResult: function () {
            function t() {
                var t = h.Deferred();
                return !0 !== e.validationResult && t.reject(), t.resolve().promise();
            }
            var e = this;
            return [t, t];
        },
        actualizeOptions: function () {
            return d.attr(this.element, this.options.namespace, this.domOptions), this.parent && this.parent.actualizeOptions && this.parent.actualizeOptions(), this;
        },
        _resetOptions: function (t) {
            for (var e in ((this.domOptions = d.objectCreate(this.parent.options)), (this.options = d.objectCreate(this.domOptions)), t)) t.hasOwnProperty(e) && (this.options[e] = t[e]);
            this.actualizeOptions();
        },
        _listeners: null,
        on: function (t, e) {
            return (this._listeners = this._listeners || {}), (this._listeners[t] = this._listeners[t] || []).push(e), this;
        },
        subscribe: function (t, e) {
            h.listenTo(this, t.toLowerCase(), e);
        },
        off: function (t, e) {
            var i = this._listeners && this._listeners[t];
            if (i)
                if (e) for (var r = i.length; r--; ) i[r] === e && i.splice(r, 1);
                else delete this._listeners[t];
            return this;
        },
        unsubscribe: function (t) {
            h.unsubscribeTo(this, t.toLowerCase());
        },
        trigger: function (t, e, i) {
            e = e || this;
            var r,
                n = this._listeners && this._listeners[t];
            if (n) for (var s = n.length; s--; ) if (!1 === (r = n[s].call(e, e, i))) return r;
            return !this.parent || this.parent.trigger(t, e, i);
        },
        asyncIsValid: function (t, e) {
            return d.warnOnce("asyncIsValid is deprecated; please use whenValid instead"), this.whenValid({ group: t, force: e });
        },
        _findRelated: function () {
            return this.options.multiple ? h(this.parent.element.querySelectorAll("[".concat(this.options.namespace, 'multiple="').concat(this.options.multiple, '"]'))) : this.$element;
        },
    };
    function c(t) {
        h.extend(!0, this, t);
    }
    c.prototype = {
        validate: function (t, e) {
            if (this.fn) return 3 < arguments.length && (e = [].slice.call(arguments, 1, -1)), this.fn(t, e);
            if (Array.isArray(t)) {
                if (!this.validateMultiple) throw "Validator `" + this.name + "` does not handle multiple values";
                return this.validateMultiple.apply(this, arguments);
            }
            var i = arguments[arguments.length - 1];
            if (this.validateDate && i._isDateInput()) return (arguments[0] = d.parse.date(arguments[0])), null !== arguments[0] && this.validateDate.apply(this, arguments);
            if (this.validateNumber) return !t || (!isNaN(t) && ((arguments[0] = parseFloat(arguments[0])), this.validateNumber.apply(this, arguments)));
            if (this.validateString) return this.validateString.apply(this, arguments);
            throw "Validator `" + this.name + "` only handles multiple values";
        },
        parseRequirements: function (t, e) {
            if ("string" != typeof t) return Array.isArray(t) ? t : [t];
            var i = this.requirementType;
            if (Array.isArray(i)) {
                for (
                    var r = (function (t, e) {
                            var i = t.match(/^\s*\[(.*)\]\s*$/);
                            if (!i) throw 'Requirement is not an array: "' + t + '"';
                            var r = i[1].split(",").map(d.trimString);
                            if (r.length !== e) throw "Requirement has " + r.length + " values when " + e + " are needed";
                            return r;
                        })(t, i.length),
                        n = 0;
                    n < r.length;
                    n++
                )
                    r[n] = d.parseRequirement(i[n], r[n]);
                return r;
            }
            return h.isPlainObject(i)
                ? (function (t, e, i) {
                      var r = null,
                          n = {};
                      for (var s in t)
                          if (s) {
                              var a = i(s);
                              "string" == typeof a && (a = d.parseRequirement(t[s], a)), (n[s] = a);
                          } else r = d.parseRequirement(t[s], e);
                      return [r, n];
                  })(i, t, e)
                : [d.parseRequirement(i, t)];
        },
        requirementType: "string",
        priority: 2,
    };
    function a(t, e) {
        (this.__class__ = "ValidatorRegistry"), (this.locale = "en"), this.init(t || {}, e || {});
    }
    var p = {
        email: /^((([a-zA-Z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-zA-Z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))$/,
        number: /^-?(\d*\.)?\d+(e[-+]?\d+)?$/i,
        integer: /^-?\d+$/,
        digits: /^\d+$/,
        alphanum: /^\w+$/i,
        date: {
            test: function (t) {
                return null !== d.parse.date(t);
            },
        },
        url: new RegExp(
            "^(?:(?:https?|ftp)://)?(?:\\S+(?::\\S*)?@)?(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-zA-Z\\u00a1-\\uffff0-9]-*)*[a-zA-Z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-zA-Z\\u00a1-\\uffff0-9]-*)*[a-zA-Z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-zA-Z\\u00a1-\\uffff]{2,})))(?::\\d{2,5})?(?:/\\S*)?$"
        ),
    };
    p.range = p.number;
    function f(t) {
        var e = ("" + t).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
        return e ? Math.max(0, (e[1] ? e[1].length : 0) - (e[2] ? +e[2] : 0)) : 0;
    }
    function m(s, a) {
        return function (t) {
            for (var e = arguments.length, i = new Array(1 < e ? e - 1 : 0), r = 1; r < e; r++) i[r - 1] = arguments[r];
            return i.pop(), a.apply(void 0, [t].concat(u(((n = s), i.map(d.parse[n])))));
            var n;
        };
    }
    function g(t) {
        return { validateDate: m("date", t), validateNumber: m("number", t), requirementType: t.length <= 2 ? "string" : ["string", "string"], priority: 30 };
    }
    a.prototype = {
        init: function (t, e) {
            for (var i in ((this.catalog = e), (this.validators = l({}, this.validators)), t)) this.addValidator(i, t[i].fn, t[i].priority);
            window.Parsley.trigger("parsley:validator:init");
        },
        setLocale: function (t) {
            if (void 0 === this.catalog[t]) throw new Error(t + " is not available in the catalog");
            return (this.locale = t), this;
        },
        addCatalog: function (t, e, i) {
            return "object" === n(e) && (this.catalog[t] = e), !0 === i ? this.setLocale(t) : this;
        },
        addMessage: function (t, e, i) {
            return void 0 === this.catalog[t] && (this.catalog[t] = {}), (this.catalog[t][e] = i), this;
        },
        addMessages: function (t, e) {
            for (var i in e) this.addMessage(t, i, e[i]);
            return this;
        },
        addValidator: function (t, e, i) {
            if (this.validators[t]) d.warn('Validator "' + t + '" is already defined.');
            else if (s.hasOwnProperty(t)) return void d.warn('"' + t + '" is a restricted keyword and is not a valid validator name.');
            return this._setValidator.apply(this, arguments);
        },
        hasValidator: function (t) {
            return !!this.validators[t];
        },
        updateValidator: function (t, e, i) {
            return this.validators[t] ? this._setValidator.apply(this, arguments) : (d.warn('Validator "' + t + '" is not already defined.'), this.addValidator.apply(this, arguments));
        },
        removeValidator: function (t) {
            return this.validators[t] || d.warn('Validator "' + t + '" is not defined.'), delete this.validators[t], this;
        },
        _setValidator: function (t, e, i) {
            for (var r in ("object" !== n(e) && (e = { fn: e, priority: i }), e.validate || (e = new c(e)), (this.validators[t] = e).messages || {})) this.addMessage(r, t, e.messages[r]);
            return this;
        },
        getErrorMessage: function (t) {
            var e;
            "type" === t.name ? (e = (this.catalog[this.locale][t.name] || {})[t.requirements]) : (e = this.formatMessage(this.catalog[this.locale][t.name], t.requirements));
            return e || this.catalog[this.locale].defaultMessage || this.catalog.en.defaultMessage;
        },
        formatMessage: function (t, e) {
            if ("object" !== n(e)) return "string" == typeof t ? t.replace(/%s/i, e) : "";
            for (var i in e) t = this.formatMessage(t, e[i]);
            return t;
        },
        validators: {
            notblank: {
                validateString: function (t) {
                    return /\S/.test(t);
                },
                priority: 2,
            },
            required: {
                validateMultiple: function (t) {
                    return 0 < t.length;
                },
                validateString: function (t) {
                    return /\S/.test(t);
                },
                priority: 512,
            },
            type: {
                validateString: function (t, e, i) {
                    var r = 2 < arguments.length && void 0 !== i ? i : {},
                        n = r.step,
                        s = void 0 === n ? "any" : n,
                        a = r.base,
                        o = void 0 === a ? 0 : a,
                        l = p[e];
                    if (!l) throw new Error("validator type `" + e + "` is not supported");
                    if (!t) return !0;
                    if (!l.test(t)) return !1;
                    if ("number" === e && !/^any$/i.test(s || "")) {
                        var u = Number(t),
                            d = Math.max(f(s), f(o));
                        if (f(u) > d) return !1;
                        var h = function (t) {
                            return Math.round(t * Math.pow(10, d));
                        };
                        if ((h(u) - h(o)) % h(s) != 0) return !1;
                    }
                    return !0;
                },
                requirementType: { "": "string", step: "string", base: "number" },
                priority: 256,
            },
            pattern: {
                validateString: function (t, e) {
                    return !t || e.test(t);
                },
                requirementType: "regexp",
                priority: 64,
            },
            minlength: {
                validateString: function (t, e) {
                    return !t || t.length >= e;
                },
                requirementType: "integer",
                priority: 30,
            },
            maxlength: {
                validateString: function (t, e) {
                    return t.length <= e;
                },
                requirementType: "integer",
                priority: 30,
            },
            length: {
                validateString: function (t, e, i) {
                    return !t || (t.length >= e && t.length <= i);
                },
                requirementType: ["integer", "integer"],
                priority: 30,
            },
            mincheck: {
                validateMultiple: function (t, e) {
                    return t.length >= e;
                },
                requirementType: "integer",
                priority: 30,
            },
            maxcheck: {
                validateMultiple: function (t, e) {
                    return t.length <= e;
                },
                requirementType: "integer",
                priority: 30,
            },
            check: {
                validateMultiple: function (t, e, i) {
                    return t.length >= e && t.length <= i;
                },
                requirementType: ["integer", "integer"],
                priority: 30,
            },
            min: g(function (t, e) {
                return e <= t;
            }),
            max: g(function (t, e) {
                return t <= e;
            }),
            range: g(function (t, e, i) {
                return e <= t && t <= i;
            }),
            equalto: {
                validateString: function (t, e) {
                    if (!t) return !0;
                    var i = h(e);
                    return i.length ? t === i.val() : t === e;
                },
                priority: 256,
            },
            euvatin: {
                validateString: function (t) {
                    if (!t) return !0;
                    return /^[A-Z][A-Z][A-Za-z0-9 -]{2,}$/.test(t);
                },
                priority: 30,
            },
        },
    };
    var v = {};
    (v.Form = {
        _actualizeTriggers: function () {
            var e = this;
            this.$element.on("submit.Parsley", function (t) {
                e.onSubmitValidate(t);
            }),
                this.$element.on("click.Parsley", d._SubmitSelector, function (t) {
                    e.onSubmitButton(t);
                }),
                !1 !== this.options.uiEnabled && this.element.setAttribute("novalidate", "");
        },
        focus: function () {
            if (!(this._focusedField = null) === this.validationResult || "none" === this.options.focus) return null;
            for (var t = 0; t < this.fields.length; t++) {
                var e = this.fields[t];
                if (!0 !== e.validationResult && 0 < e.validationResult.length && void 0 === e.options.noFocus && ((this._focusedField = e.$element), "first" === this.options.focus)) break;
            }
            return null === this._focusedField ? null : this._focusedField.focus();
        },
        _destroyUI: function () {
            this.$element.off(".Parsley");
        },
    }),
        (v.Field = {
            _reflowUI: function () {
                if ((this._buildUI(), this._ui)) {
                    var t = (function t(e, i, r) {
                        for (var n = [], s = [], a = 0; a < e.length; a++) {
                            for (var o = !1, l = 0; l < i.length; l++)
                                if (e[a].assert.name === i[l].assert.name) {
                                    o = !0;
                                    break;
                                }
                            o ? s.push(e[a]) : n.push(e[a]);
                        }
                        return { kept: s, added: n, removed: r ? [] : t(i, e, !0).added };
                    })(this.validationResult, this._ui.lastValidationResult);
                    (this._ui.lastValidationResult = this.validationResult),
                        this._manageStatusClass(),
                        this._manageErrorsMessages(t),
                        this._actualizeTriggers(),
                        (!t.kept.length && !t.added.length) || this._failedOnce || ((this._failedOnce = !0), this._actualizeTriggers());
                }
            },
            getErrorsMessages: function () {
                if (!0 === this.validationResult) return [];
                for (var t = [], e = 0; e < this.validationResult.length; e++) t.push(this.validationResult[e].errorMessage || this._getErrorMessage(this.validationResult[e].assert));
                return t;
            },
            addError: function (t, e) {
                var i = 1 < arguments.length && void 0 !== e ? e : {},
                    r = i.message,
                    n = i.assert,
                    s = i.updateClass,
                    a = void 0 === s || s;
                this._buildUI(), this._addError(t, { message: r, assert: n }), a && this._errorClass();
            },
            updateError: function (t, e) {
                var i = 1 < arguments.length && void 0 !== e ? e : {},
                    r = i.message,
                    n = i.assert,
                    s = i.updateClass,
                    a = void 0 === s || s;
                this._buildUI(), this._updateError(t, { message: r, assert: n }), a && this._errorClass();
            },
            removeError: function (t, e) {
                var i = (1 < arguments.length && void 0 !== e ? e : {}).updateClass,
                    r = void 0 === i || i;
                this._buildUI(), this._removeError(t), r && this._manageStatusClass();
            },
            _manageStatusClass: function () {
                this.hasConstraints() && this.needsValidation() && !0 === this.validationResult ? this._successClass() : 0 < this.validationResult.length ? this._errorClass() : this._resetClass();
            },
            _manageErrorsMessages: function (t) {
                if (void 0 === this.options.errorsMessagesDisabled) {
                    if (void 0 !== this.options.errorMessage)
                        return t.added.length || t.kept.length
                            ? (this._insertErrorWrapper(),
                              0 === this._ui.$errorsWrapper.find(".parsley-custom-error-message").length && this._ui.$errorsWrapper.append(h(this.options.errorTemplate).addClass("parsley-custom-error-message")),
                              this._ui.$errorClassHandler.attr("aria-describedby", this._ui.errorsWrapperId),
                              this._ui.$errorsWrapper.addClass("filled").attr("aria-hidden", "false").find(".parsley-custom-error-message").html(this.options.errorMessage))
                            : (this._ui.$errorClassHandler.removeAttr("aria-describedby"), this._ui.$errorsWrapper.removeClass("filled").attr("aria-hidden", "true").find(".parsley-custom-error-message").remove());
                    for (var e = 0; e < t.removed.length; e++) this._removeError(t.removed[e].assert.name);
                    for (e = 0; e < t.added.length; e++) this._addError(t.added[e].assert.name, { message: t.added[e].errorMessage, assert: t.added[e].assert });
                    for (e = 0; e < t.kept.length; e++) this._updateError(t.kept[e].assert.name, { message: t.kept[e].errorMessage, assert: t.kept[e].assert });
                }
            },
            _addError: function (t, e) {
                var i = e.message,
                    r = e.assert;
                this._insertErrorWrapper(),
                    this._ui.$errorClassHandler.attr("aria-describedby", this._ui.errorsWrapperId),
                    this._ui.$errorsWrapper
                        .addClass("filled")
                        .attr("aria-hidden", "false")
                        .append(
                            h(this.options.errorTemplate)
                                .addClass("parsley-" + t)
                                .html(i || this._getErrorMessage(r))
                        );
            },
            _updateError: function (t, e) {
                var i = e.message,
                    r = e.assert;
                this._ui.$errorsWrapper
                    .addClass("filled")
                    .find(".parsley-" + t)
                    .html(i || this._getErrorMessage(r));
            },
            _removeError: function (t) {
                this._ui.$errorClassHandler.removeAttr("aria-describedby"),
                    this._ui.$errorsWrapper
                        .removeClass("filled")
                        .attr("aria-hidden", "true")
                        .find(".parsley-" + t)
                        .remove();
            },
            _getErrorMessage: function (t) {
                var e = t.name + "Message";
                return void 0 !== this.options[e] ? window.Parsley.formatMessage(this.options[e], t.requirements) : window.Parsley.getErrorMessage(t);
            },
            _buildUI: function () {
                if (!this._ui && !1 !== this.options.uiEnabled) {
                    var t = {};
                    this.element.setAttribute(this.options.namespace + "id", this.__id__),
                        (t.$errorClassHandler = this._manageClassHandler()),
                        (t.errorsWrapperId = "parsley-id-" + (this.options.multiple ? "multiple-" + this.options.multiple : this.__id__)),
                        (t.$errorsWrapper = h(this.options.errorsWrapper).attr("id", t.errorsWrapperId)),
                        (t.lastValidationResult = []),
                        (t.validationInformationVisible = !1),
                        (this._ui = t);
                }
            },
            _manageClassHandler: function () {
                if ("string" == typeof this.options.classHandler && h(this.options.classHandler).length) return h(this.options.classHandler);
                var t = this.options.classHandler;
                if (("string" == typeof this.options.classHandler && "function" == typeof window[this.options.classHandler] && (t = window[this.options.classHandler]), "function" == typeof t)) {
                    var e = t.call(this, this);
                    if (void 0 !== e && e.length) return e;
                } else {
                    if ("object" === n(t) && t instanceof jQuery && t.length) return t;
                    t && d.warn("The class handler `" + t + "` does not exist in DOM nor as a global JS function");
                }
                return this._inputHolder();
            },
            _inputHolder: function () {
                return this.options.multiple && "SELECT" !== this.element.nodeName ? this.$element.parent() : this.$element;
            },
            _insertErrorWrapper: function () {
                var t = this.options.errorsContainer;
                if (0 !== this._ui.$errorsWrapper.parent().length) return this._ui.$errorsWrapper.parent();
                if ("string" == typeof t) {
                    if (h(t).length) return h(t).append(this._ui.$errorsWrapper);
                    "function" == typeof window[t] ? (t = window[t]) : d.warn("The errors container `" + t + "` does not exist in DOM nor as a global JS function");
                }
                return "function" == typeof t && (t = t.call(this, this)), "object" === n(t) && t.length ? t.append(this._ui.$errorsWrapper) : this._inputHolder().after(this._ui.$errorsWrapper);
            },
            _actualizeTriggers: function () {
                var t,
                    e = this,
                    i = this._findRelated();
                i.off(".Parsley"),
                    this._failedOnce
                        ? i.on(d.namespaceEvents(this.options.triggerAfterFailure, "Parsley"), function () {
                              e._validateIfNeeded();
                          })
                        : (t = d.namespaceEvents(this.options.trigger, "Parsley")) &&
                          i.on(t, function (t) {
                              e._validateIfNeeded(t);
                          });
            },
            _validateIfNeeded: function (t) {
                var e = this;
                (t && /key|input/.test(t.type) && (!this._ui || !this._ui.validationInformationVisible) && this.getValue().length <= this.options.validationThreshold) ||
                    (this.options.debounce
                        ? (window.clearTimeout(this._debounced),
                          (this._debounced = window.setTimeout(function () {
                              return e.validate();
                          }, this.options.debounce)))
                        : this.validate());
            },
            _resetUI: function () {
                (this._failedOnce = !1),
                    this._actualizeTriggers(),
                    void 0 !== this._ui && (this._ui.$errorsWrapper.removeClass("filled").children().remove(), this._resetClass(), (this._ui.lastValidationResult = []), (this._ui.validationInformationVisible = !1));
            },
            _destroyUI: function () {
                this._resetUI(), void 0 !== this._ui && this._ui.$errorsWrapper.remove(), delete this._ui;
            },
            _successClass: function () {
                (this._ui.validationInformationVisible = !0), this._ui.$errorClassHandler.removeClass(this.options.errorClass).addClass(this.options.successClass);
            },
            _errorClass: function () {
                (this._ui.validationInformationVisible = !0), this._ui.$errorClassHandler.removeClass(this.options.successClass).addClass(this.options.errorClass);
            },
            _resetClass: function () {
                this._ui.$errorClassHandler.removeClass(this.options.successClass).removeClass(this.options.errorClass);
            },
        });
    function y(t, e, i) {
        (this.__class__ = "Form"), (this.element = t), (this.$element = h(t)), (this.domOptions = e), (this.options = i), (this.parent = window.Parsley), (this.fields = []), (this.validationResult = null);
    }
    var _ = { pending: null, resolved: !0, rejected: !1 };
    y.prototype = {
        onSubmitValidate: function (t) {
            var e = this;
            if (!0 !== t.parsley) {
                var i = this._submitSource || this.$element.find(d._SubmitSelector)[0];
                if (((this._submitSource = null), this.$element.find(".parsley-synthetic-submit-button").prop("disabled", !0), !i || null === i.getAttribute("formnovalidate"))) {
                    window.Parsley._remoteCache = {};
                    var r = this.whenValidate({ event: t });
                    ("resolved" === r.state() && !1 !== this._trigger("submit")) ||
                        (t.stopImmediatePropagation(),
                        t.preventDefault(),
                        "pending" === r.state() &&
                            r.done(function () {
                                e._submit(i);
                            }));
                }
            }
        },
        onSubmitButton: function (t) {
            this._submitSource = t.currentTarget;
        },
        _submit: function (t) {
            if (!1 !== this._trigger("submit")) {
                if (t) {
                    var e = this.$element.find(".parsley-synthetic-submit-button").prop("disabled", !1);
                    0 === e.length && (e = h('<input class="parsley-synthetic-submit-button" type="hidden">').appendTo(this.$element)), e.attr({ name: t.getAttribute("name"), value: t.getAttribute("value") });
                }
                this.$element.trigger(l(h.Event("submit"), { parsley: !0 }));
            }
        },
        validate: function (t) {
            if (1 <= arguments.length && !h.isPlainObject(t)) {
                d.warnOnce("Calling validate on a parsley form without passing arguments as an object is deprecated.");
                var e = Array.prototype.slice.call(arguments);
                t = { group: e[0], force: e[1], event: e[2] };
            }
            return _[this.whenValidate(t).state()];
        },
        whenValidate: function (t) {
            var e,
                i = this,
                r = 0 < arguments.length && void 0 !== t ? t : {},
                n = r.group,
                s = r.force,
                a = r.event;
            (this.submitEvent = a) &&
                (this.submitEvent = l({}, a, {
                    preventDefault: function () {
                        d.warnOnce("Using `this.submitEvent.preventDefault()` is deprecated; instead, call `this.validationResult = false`"), (i.validationResult = !1);
                    },
                })),
                (this.validationResult = !0),
                this._trigger("validate"),
                this._refreshFields();
            var o = this._withoutReactualizingFormOptions(function () {
                return h.map(i.fields, function (t) {
                    return t.whenValidate({ force: s, group: n });
                });
            });
            return (e = d
                .all(o)
                .done(function () {
                    i._trigger("success");
                })
                .fail(function () {
                    (i.validationResult = !1), i.focus(), i._trigger("error");
                })
                .always(function () {
                    i._trigger("validated");
                })).pipe.apply(e, u(this._pipeAccordingToValidationResult()));
        },
        isValid: function (t) {
            if (1 <= arguments.length && !h.isPlainObject(t)) {
                d.warnOnce("Calling isValid on a parsley form without passing arguments as an object is deprecated.");
                var e = Array.prototype.slice.call(arguments);
                t = { group: e[0], force: e[1] };
            }
            return _[this.whenValid(t).state()];
        },
        whenValid: function (t) {
            var e = this,
                i = 0 < arguments.length && void 0 !== t ? t : {},
                r = i.group,
                n = i.force;
            this._refreshFields();
            var s = this._withoutReactualizingFormOptions(function () {
                return h.map(e.fields, function (t) {
                    return t.whenValid({ group: r, force: n });
                });
            });
            return d.all(s);
        },
        refresh: function () {
            return this._refreshFields(), this;
        },
        reset: function () {
            for (var t = 0; t < this.fields.length; t++) this.fields[t].reset();
            this._trigger("reset");
        },
        destroy: function () {
            this._destroyUI();
            for (var t = 0; t < this.fields.length; t++) this.fields[t].destroy();
            this.$element.removeData("Parsley"), this._trigger("destroy");
        },
        _refreshFields: function () {
            return this.actualizeOptions()._bindFields();
        },
        _bindFields: function () {
            var n = this,
                t = this.fields;
            return (
                (this.fields = []),
                (this.fieldsMappedById = {}),
                this._withoutReactualizingFormOptions(function () {
                    n.$element
                        .find(n.options.inputs)
                        .not(n.options.excluded)
                        .not("[".concat(n.options.namespace, "excluded=true]"))
                        .each(function (t, e) {
                            var i = new window.Parsley.Factory(e, {}, n);
                            if ("Field" === i.__class__ || "FieldMultiple" === i.__class__) {
                                var r = i.__class__ + "-" + i.__id__;
                                void 0 === n.fieldsMappedById[r] && ((n.fieldsMappedById[r] = i), n.fields.push(i));
                            }
                        }),
                        h.each(d.difference(t, n.fields), function (t, e) {
                            e.reset();
                        });
                }),
                this
            );
        },
        _withoutReactualizingFormOptions: function (t) {
            var e = this.actualizeOptions;
            this.actualizeOptions = function () {
                return this;
            };
            var i = t();
            return (this.actualizeOptions = e), i;
        },
        _trigger: function (t) {
            return this.trigger("form:" + t);
        },
    };
    function b(t, e, i, r, n) {
        var s = window.Parsley._validatorRegistry.validators[e],
            a = new c(s);
        l(this, { validator: a, name: e, requirements: i, priority: (r = r || t.options[e + "Priority"] || a.priority), isDomConstraint: (n = !0 === n) }), this._parseRequirements(t.options);
    }
    function w(t, e, i, r) {
        (this.__class__ = "Field"),
            (this.element = t),
            (this.$element = h(t)),
            void 0 !== r && (this.parent = r),
            (this.options = i),
            (this.domOptions = e),
            (this.constraints = []),
            (this.constraintsByName = {}),
            (this.validationResult = !0),
            this._bindConstraints();
    }
    var F = {
        pending: null,
        resolved: !0,
        rejected: !(b.prototype = {
            validate: function (t, e) {
                var i;
                return (i = this.validator).validate.apply(i, [t].concat(u(this.requirementList), [e]));
            },
            _parseRequirements: function (i) {
                var r = this;
                this.requirementList = this.validator.parseRequirements(this.requirements, function (t) {
                    return i[r.name + ((e = t)[0].toUpperCase() + e.slice(1))];
                    var e;
                });
            },
        }),
    };
    w.prototype = {
        validate: function (t) {
            1 <= arguments.length && !h.isPlainObject(t) && (d.warnOnce("Calling validate on a parsley field without passing arguments as an object is deprecated."), (t = { options: t }));
            var e = this.whenValidate(t);
            if (!e) return !0;
            switch (e.state()) {
                case "pending":
                    return null;
                case "resolved":
                    return !0;
                case "rejected":
                    return this.validationResult;
            }
        },
        whenValidate: function (t) {
            var e,
                i = this,
                r = 0 < arguments.length && void 0 !== t ? t : {},
                n = r.force,
                s = r.group;
            if ((this.refresh(), !s || this._isInGroup(s)))
                return (
                    (this.value = this.getValue()),
                    this._trigger("validate"),
                    (e = this.whenValid({ force: n, value: this.value, _refreshed: !0 })
                        .always(function () {
                            i._reflowUI();
                        })
                        .done(function () {
                            i._trigger("success");
                        })
                        .fail(function () {
                            i._trigger("error");
                        })
                        .always(function () {
                            i._trigger("validated");
                        })).pipe.apply(e, u(this._pipeAccordingToValidationResult()))
                );
        },
        hasConstraints: function () {
            return 0 !== this.constraints.length;
        },
        needsValidation: function (t) {
            return void 0 === t && (t = this.getValue()), !(!t.length && !this._isRequired() && void 0 === this.options.validateIfEmpty);
        },
        _isInGroup: function (t) {
            return Array.isArray(this.options.group) ? -1 !== h.inArray(t, this.options.group) : this.options.group === t;
        },
        isValid: function (t) {
            if (1 <= arguments.length && !h.isPlainObject(t)) {
                d.warnOnce("Calling isValid on a parsley field without passing arguments as an object is deprecated.");
                var e = Array.prototype.slice.call(arguments);
                t = { force: e[0], value: e[1] };
            }
            var i = this.whenValid(t);
            return !i || F[i.state()];
        },
        whenValid: function (t) {
            var r = this,
                e = 0 < arguments.length && void 0 !== t ? t : {},
                i = e.force,
                n = void 0 !== i && i,
                s = e.value,
                a = e.group;
            if ((e._refreshed || this.refresh(), !a || this._isInGroup(a))) {
                if (((this.validationResult = !0), !this.hasConstraints())) return h.when();
                if ((null == s && (s = this.getValue()), !this.needsValidation(s) && !0 !== n)) return h.when();
                var o = this._getGroupedConstraints(),
                    l = [];
                return (
                    h.each(o, function (t, e) {
                        var i = d.all(
                            h.map(e, function (t) {
                                return r._validateConstraint(s, t);
                            })
                        );
                        if ((l.push(i), "rejected" === i.state())) return !1;
                    }),
                    d.all(l)
                );
            }
        },
        _validateConstraint: function (t, e) {
            var i = this,
                r = e.validate(t, this);
            return (
                !1 === r && (r = h.Deferred().reject()),
                d.all([r]).fail(function (t) {
                    i.validationResult instanceof Array || (i.validationResult = []), i.validationResult.push({ assert: e, errorMessage: "string" == typeof t && t });
                })
            );
        },
        getValue: function () {
            var t;
            return null == (t = "function" == typeof this.options.value ? this.options.value(this) : void 0 !== this.options.value ? this.options.value : this.$element.val()) ? "" : this._handleWhitespace(t);
        },
        reset: function () {
            return this._resetUI(), this._trigger("reset");
        },
        destroy: function () {
            this._destroyUI(), this.$element.removeData("Parsley"), this.$element.removeData("FieldMultiple"), this._trigger("destroy");
        },
        refresh: function () {
            return this._refreshConstraints(), this;
        },
        _refreshConstraints: function () {
            return this.actualizeOptions()._bindConstraints();
        },
        refreshConstraints: function () {
            return d.warnOnce("Parsley's refreshConstraints is deprecated. Please use refresh"), this.refresh();
        },
        addConstraint: function (t, e, i, r) {
            if (window.Parsley._validatorRegistry.validators[t]) {
                var n = new b(this, t, e, i, r);
                "undefined" !== this.constraintsByName[n.name] && this.removeConstraint(n.name), this.constraints.push(n), (this.constraintsByName[n.name] = n);
            }
            return this;
        },
        removeConstraint: function (t) {
            for (var e = 0; e < this.constraints.length; e++)
                if (t === this.constraints[e].name) {
                    this.constraints.splice(e, 1);
                    break;
                }
            return delete this.constraintsByName[t], this;
        },
        updateConstraint: function (t, e, i) {
            return this.removeConstraint(t).addConstraint(t, e, i);
        },
        _bindConstraints: function () {
            for (var t = [], e = {}, i = 0; i < this.constraints.length; i++) !1 === this.constraints[i].isDomConstraint && (t.push(this.constraints[i]), (e[this.constraints[i].name] = this.constraints[i]));
            for (var r in ((this.constraints = t), (this.constraintsByName = e), this.options)) this.addConstraint(r, this.options[r], void 0, !0);
            return this._bindHtml5Constraints();
        },
        _bindHtml5Constraints: function () {
            null !== this.element.getAttribute("required") && this.addConstraint("required", !0, void 0, !0), null !== this.element.getAttribute("pattern") && this.addConstraint("pattern", this.element.getAttribute("pattern"), void 0, !0);
            var t = this.element.getAttribute("min"),
                e = this.element.getAttribute("max");
            null !== t && null !== e ? this.addConstraint("range", [t, e], void 0, !0) : null !== t ? this.addConstraint("min", t, void 0, !0) : null !== e && this.addConstraint("max", e, void 0, !0),
                null !== this.element.getAttribute("minlength") && null !== this.element.getAttribute("maxlength")
                    ? this.addConstraint("length", [this.element.getAttribute("minlength"), this.element.getAttribute("maxlength")], void 0, !0)
                    : null !== this.element.getAttribute("minlength")
                    ? this.addConstraint("minlength", this.element.getAttribute("minlength"), void 0, !0)
                    : null !== this.element.getAttribute("maxlength") && this.addConstraint("maxlength", this.element.getAttribute("maxlength"), void 0, !0);
            var i = d.getType(this.element);
            return "number" === i
                ? this.addConstraint("type", ["number", { step: this.element.getAttribute("step") || "1", base: t || this.element.getAttribute("value") }], void 0, !0)
                : /^(email|url|range|date)$/i.test(i)
                ? this.addConstraint("type", i, void 0, !0)
                : this;
        },
        _isRequired: function () {
            return void 0 !== this.constraintsByName.required && !1 !== this.constraintsByName.required.requirements;
        },
        _trigger: function (t) {
            return this.trigger("field:" + t);
        },
        _handleWhitespace: function (t) {
            return (
                !0 === this.options.trimValue && d.warnOnce('data-parsley-trim-value="true" is deprecated, please use data-parsley-whitespace="trim"'),
                "squish" === this.options.whitespace && (t = t.replace(/\s{2,}/g, " ")),
                ("trim" !== this.options.whitespace && "squish" !== this.options.whitespace && !0 !== this.options.trimValue) || (t = d.trimString(t)),
                t
            );
        },
        _isDateInput: function () {
            var t = this.constraintsByName.type;
            return t && "date" === t.requirements;
        },
        _getGroupedConstraints: function () {
            if (!1 === this.options.priorityEnabled) return [this.constraints];
            for (var t = [], e = {}, i = 0; i < this.constraints.length; i++) {
                var r = this.constraints[i].priority;
                e[r] || t.push((e[r] = [])), e[r].push(this.constraints[i]);
            }
            return (
                t.sort(function (t, e) {
                    return e[0].priority - t[0].priority;
                }),
                t
            );
        },
    };
    function C() {
        this.__class__ = "FieldMultiple";
    }
    C.prototype = {
        addElement: function (t) {
            return this.$elements.push(t), this;
        },
        _refreshConstraints: function () {
            var t;
            if (((this.constraints = []), "SELECT" === this.element.nodeName)) return this.actualizeOptions()._bindConstraints(), this;
            for (var e = 0; e < this.$elements.length; e++)
                if (h("html").has(this.$elements[e]).length) {
                    t = this.$elements[e].data("FieldMultiple")._refreshConstraints().constraints;
                    for (var i = 0; i < t.length; i++) this.addConstraint(t[i].name, t[i].requirements, t[i].priority, t[i].isDomConstraint);
                } else this.$elements.splice(e, 1);
            return this;
        },
        getValue: function () {
            if ("function" == typeof this.options.value) return this.options.value(this);
            if (void 0 !== this.options.value) return this.options.value;
            if ("INPUT" === this.element.nodeName) {
                var t = d.getType(this.element);
                if ("radio" === t) return this._findRelated().filter(":checked").val() || "";
                if ("checkbox" === t) {
                    var e = [];
                    return (
                        this._findRelated()
                            .filter(":checked")
                            .each(function () {
                                e.push(h(this).val());
                            }),
                        e
                    );
                }
            }
            return "SELECT" === this.element.nodeName && null === this.$element.val() ? [] : this.$element.val();
        },
        _init: function () {
            return (this.$elements = [this.$element]), this;
        },
    };
    function A(t, e, i) {
        (this.element = t), (this.$element = h(t));
        var r = this.$element.data("Parsley");
        if (r) return void 0 !== i && r.parent === window.Parsley && ((r.parent = i), r._resetOptions(r.options)), "object" === n(e) && l(r.options, e), r;
        if (!this.$element.length) throw new Error("You must bind Parsley on an existing element.");
        if (void 0 !== i && "Form" !== i.__class__) throw new Error("Parent instance must be a Form instance");
        return (this.parent = i || window.Parsley), this.init(e);
    }
    A.prototype = {
        init: function (t) {
            return (
                (this.__class__ = "Parsley"),
                (this.__version__ = "2.9.2"),
                (this.__id__ = d.generateID()),
                this._resetOptions(t),
                "FORM" === this.element.nodeName || (d.checkAttr(this.element, this.options.namespace, "validate") && !this.$element.is(this.options.inputs))
                    ? this.bind("parsleyForm")
                    : this.isMultiple()
                    ? this.handleMultiple()
                    : this.bind("parsleyField")
            );
        },
        isMultiple: function () {
            var t = d.getType(this.element);
            return "radio" === t || "checkbox" === t || ("SELECT" === this.element.nodeName && null !== this.element.getAttribute("multiple"));
        },
        handleMultiple: function () {
            var t,
                e,
                r = this;
            if (((this.options.multiple = this.options.multiple || (t = this.element.getAttribute("name")) || this.element.getAttribute("id")), "SELECT" === this.element.nodeName && null !== this.element.getAttribute("multiple")))
                return (this.options.multiple = this.options.multiple || this.__id__), this.bind("parsleyFieldMultiple");
            if (!this.options.multiple) return d.warn("To be bound by Parsley, a radio, a checkbox and a multiple select input must have either a name or a multiple option.", this.$element), this;
            (this.options.multiple = this.options.multiple.replace(/(:|\.|\[|\]|\{|\}|\$)/g, "")),
                t &&
                    h('input[name="' + t + '"]').each(function (t, e) {
                        var i = d.getType(e);
                        ("radio" !== i && "checkbox" !== i) || e.setAttribute(r.options.namespace + "multiple", r.options.multiple);
                    });
            for (var i = this._findRelated(), n = 0; n < i.length; n++)
                if (void 0 !== (e = h(i.get(n)).data("Parsley"))) {
                    this.$element.data("FieldMultiple") || e.addElement(this.$element);
                    break;
                }
            return this.bind("parsleyField", !0), e || this.bind("parsleyFieldMultiple");
        },
        bind: function (t, e) {
            var i;
            switch (t) {
                case "parsleyForm":
                    i = h.extend(new y(this.element, this.domOptions, this.options), new r(), window.ParsleyExtend)._bindFields();
                    break;
                case "parsleyField":
                    i = h.extend(new w(this.element, this.domOptions, this.options, this.parent), new r(), window.ParsleyExtend);
                    break;
                case "parsleyFieldMultiple":
                    i = h.extend(new w(this.element, this.domOptions, this.options, this.parent), new C(), new r(), window.ParsleyExtend)._init();
                    break;
                default:
                    throw new Error(t + "is not a supported Parsley type");
            }
            return (
                this.options.multiple && d.setAttr(this.element, this.options.namespace, "multiple", this.options.multiple),
                void 0 !== e ? this.$element.data("FieldMultiple", i) : (this.$element.data("Parsley", i), i._actualizeTriggers(), i._trigger("init")),
                i
            );
        },
    };
    var E = h.fn.jquery.split(".");
    if (parseInt(E[0]) <= 1 && parseInt(E[1]) < 8) throw "The loaded version of jQuery is too old. Please upgrade to 1.8.x or better.";
    E.forEach || d.warn("Parsley requires ES5 to run properly. Please include https://github.com/es-shims/es5-shim");
    var x = l(new r(), { element: document, $element: h(document), actualizeOptions: null, _resetOptions: null, Factory: A, version: "2.9.2" });
    l(w.prototype, v.Field, r.prototype),
        l(y.prototype, v.Form, r.prototype),
        l(A.prototype, r.prototype),
        (h.fn.parsley = h.fn.psly = function (t) {
            if (1 < this.length) {
                var e = [];
                return (
                    this.each(function () {
                        e.push(h(this).parsley(t));
                    }),
                    e
                );
            }
            if (0 != this.length) return new A(this[0], t);
        }),
        void 0 === window.ParsleyExtend && (window.ParsleyExtend = {}),
        (x.options = l(d.objectCreate(s), window.ParsleyConfig)),
        (window.ParsleyConfig = x.options),
        (window.Parsley = window.psly = x),
        (x.Utils = d),
        (window.ParsleyUtils = {}),
        h.each(d, function (t, e) {
            "function" == typeof e &&
                (window.ParsleyUtils[t] = function () {
                    return d.warnOnce("Accessing `window.ParsleyUtils` is deprecated. Use `window.Parsley.Utils` instead."), d[t].apply(d, arguments);
                });
        });
    var $ = (window.Parsley._validatorRegistry = new a(window.ParsleyConfig.validators, window.ParsleyConfig.i18n));
    (window.ParsleyValidator = {}),
        h.each("setLocale addCatalog addMessage addMessages getErrorMessage formatMessage addValidator updateValidator removeValidator hasValidator".split(" "), function (t, e) {
            (window.Parsley[e] = function () {
                return $[e].apply($, arguments);
            }),
                (window.ParsleyValidator[e] = function () {
                    var t;
                    return d.warnOnce("Accessing the method '".concat(e, "' through Validator is deprecated. Simply call 'window.Parsley.").concat(e, "(...)'")), (t = window.Parsley)[e].apply(t, arguments);
                });
        }),
        (window.Parsley.UI = v),
        (window.ParsleyUI = {
            removeError: function (t, e, i) {
                var r = !0 !== i;
                return d.warnOnce("Accessing UI is deprecated. Call 'removeError' on the instance directly. Please comment in issue 1073 as to your need to call this method."), t.removeError(e, { updateClass: r });
            },
            getErrorsMessages: function (t) {
                return d.warnOnce("Accessing UI is deprecated. Call 'getErrorsMessages' on the instance directly."), t.getErrorsMessages();
            },
        }),
        h.each("addError updateError".split(" "), function (t, a) {
            window.ParsleyUI[a] = function (t, e, i, r, n) {
                var s = !0 !== n;
                return d.warnOnce("Accessing UI is deprecated. Call '".concat(a, "' on the instance directly. Please comment in issue 1073 as to your need to call this method.")), t[a](e, { message: i, assert: r, updateClass: s });
            };
        }),
        !1 !== window.ParsleyConfig.autoBind &&
            h(function () {
                h("[data-parsley-validate]").length && h("[data-parsley-validate]").parsley();
            });
    function V() {
        d.warnOnce("Parsley's pubsub module is deprecated; use the 'on' and 'off' methods on parsley instances or window.Parsley");
    }
    var P = h({});
    function O(e, i) {
        return (
            e.parsleyAdaptedCallback ||
                (e.parsleyAdaptedCallback = function () {
                    var t = Array.prototype.slice.call(arguments, 0);
                    t.unshift(this), e.apply(i || P, t);
                }),
            e.parsleyAdaptedCallback
        );
    }
    var T = "parsley:";
    function M(t) {
        return 0 === t.lastIndexOf(T, 0) ? t.substr(T.length) : t;
    }
    return (
        (h.listen = function (t, e) {
            var i;
            if ((V(), "object" === n(arguments[1]) && "function" == typeof arguments[2] && ((i = arguments[1]), (e = arguments[2])), "function" != typeof e)) throw new Error("Wrong parameters");
            window.Parsley.on(M(t), O(e, i));
        }),
        (h.listenTo = function (t, e, i) {
            if ((V(), !(t instanceof w || t instanceof y))) throw new Error("Must give Parsley instance");
            if ("string" != typeof e || "function" != typeof i) throw new Error("Wrong parameters");
            t.on(M(e), O(i));
        }),
        (h.unsubscribe = function (t, e) {
            if ((V(), "string" != typeof t || "function" != typeof e)) throw new Error("Wrong arguments");
            window.Parsley.off(M(t), e.parsleyAdaptedCallback);
        }),
        (h.unsubscribeTo = function (t, e) {
            if ((V(), !(t instanceof w || t instanceof y))) throw new Error("Must give Parsley instance");
            t.off(M(e));
        }),
        (h.unsubscribeAll = function (e) {
            V(),
                window.Parsley.off(M(e)),
                h("form,input,textarea,select").each(function () {
                    var t = h(this).data("Parsley");
                    t && t.off(M(e));
                });
        }),
        (h.emit = function (t, e) {
            V();
            var i = e instanceof w || e instanceof y,
                r = Array.prototype.slice.call(arguments, i ? 2 : 1);
            r.unshift(M(t)), i || (e = window.Parsley), e.trigger.apply(e, u(r));
        }),
        h.extend(!0, x, {
            asyncValidators: {
                default: {
                    fn: function (t) {
                        return 200 <= t.status && t.status < 300;
                    },
                    url: !1,
                },
                reverse: {
                    fn: function (t) {
                        return t.status < 200 || 300 <= t.status;
                    },
                    url: !1,
                },
            },
            addAsyncValidator: function (t, e, i, r) {
                return (x.asyncValidators[t] = { fn: e, url: i || !1, options: r || {} }), this;
            },
        }),
        x.addValidator("remote", {
            requirementType: { "": "string", validator: "string", reverse: "boolean", options: "object" },
            validateString: function (t, e, i, r) {
                var n,
                    s,
                    a = {},
                    o = i.validator || (!0 === i.reverse ? "reverse" : "default");
                if (void 0 === x.asyncValidators[o]) throw new Error("Calling an undefined async validator: `" + o + "`");
                -1 < (e = x.asyncValidators[o].url || e).indexOf("{value}") ? (e = e.replace("{value}", encodeURIComponent(t))) : (a[r.element.getAttribute("name") || r.element.getAttribute("id")] = t);
                var l = h.extend(!0, i.options || {}, x.asyncValidators[o].options);
                (n = h.extend(!0, {}, { url: e, data: a, type: "GET" }, l)), r.trigger("field:ajaxoptions", r, n), (s = h.param(n)), void 0 === x._remoteCache && (x._remoteCache = {});
                function u() {
                    var t = x.asyncValidators[o].fn.call(r, d, e, i);
                    return (t = t || h.Deferred().reject()), h.when(t);
                }
                var d = (x._remoteCache[s] = x._remoteCache[s] || h.ajax(n));
                return d.then(u, u);
            },
            priority: -1,
        }),
        x.on("form:submit", function () {
            x._remoteCache = {};
        }),
        (r.prototype.addAsyncValidator = function () {
            return d.warnOnce("Accessing the method `addAsyncValidator` through an instance is deprecated. Simply call `Parsley.addAsyncValidator(...)`"), x.addAsyncValidator.apply(x, arguments);
        }),
        x.addMessages("en", {
            defaultMessage: "This value seems to be invalid.",
            type: {
                email: "Por favor, insira um e-mail válido.",
                url: "This value should be a valid url.",
                number: "This value should be a valid number.",
                integer: "This value should be a valid integer.",
                digits: "This value should be digits.",
                alphanum: "This value should be alphanumeric.",
            },
            notblank: "This value should not be blank.",
            required: "Esse campo é obrigatório.",
            pattern: "This value seems to be invalid.",
            min: "This value should be greater than or equal to %s.",
            max: "This value should be lower than or equal to %s.",
            range: "This value should be between %s and %s.",
            minlength: "This value is too short. It should have %s characters or more.",
            maxlength: "This value is too long. It should have %s characters or fewer.",
            length: "This value length is invalid. It should be between %s and %s characters long.",
            mincheck: "You must select at least %s choices.",
            maxcheck: "You must select %s choices or fewer.",
            check: "You must select between %s and %s choices.",
            equalto: "This value should be the same.",
            euvatin: "It's not a valid VAT Identification Number.",
        }),
        x.setLocale("en"),
        new (function () {
            var r = this,
                n = window || global;
            l(this, {
                isNativeEvent: function (t) {
                    return t.originalEvent && !1 !== t.originalEvent.isTrusted;
                },
                fakeInputEvent: function (t) {
                    r.isNativeEvent(t) && h(t.target).trigger("input");
                },
                misbehaves: function (t) {
                    r.isNativeEvent(t) && (r.behavesOk(t), h(document).on("change.inputevent", t.data.selector, r.fakeInputEvent), r.fakeInputEvent(t));
                },
                behavesOk: function (t) {
                    r.isNativeEvent(t) && h(document).off("input.inputevent", t.data.selector, r.behavesOk).off("change.inputevent", t.data.selector, r.misbehaves);
                },
                install: function () {
                    if (!n.inputEventPatched) {
                        n.inputEventPatched = "0.0.3";
                        for (var t = 0, e = ["select", 'input[type="checkbox"]', 'input[type="radio"]', 'input[type="file"]']; t < e.length; t++) {
                            var i = e[t];
                            h(document).on("input.inputevent", i, { selector: i }, r.behavesOk).on("change.inputevent", i, { selector: i }, r.misbehaves);
                        }
                    }
                },
                uninstall: function () {
                    delete n.inputEventPatched, h(document).off(".inputevent");
                },
            });
        })().install(),
        x
    );
});
//# sourceMappingURL=parsley.min.js.map
