"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.ServerStyles = void 0;
const react_1 = __importDefault(require("react"));
require("../../global");
const useBaseUrl_1 = __importDefault(require("@docusaurus/useBaseUrl"));
const redoc_1 = require("redoc");
// eslint-disable-next-line import/no-extraneous-dependencies
const server_1 = require("react-dom/server");
const styled_components_1 = require("styled-components");
/**
 * @see https://stackoverflow.com/a/54077142
 */
const prefixCssSelectors = function (rules, className) {
    const classLen = className.length;
    let char, nextChar, isAt, isIn;
    // makes sure the className will not concatenate the selector
    className += ' ';
    // removes comments
    rules = rules.replace(/\/\*(?:(?!\*\/)[\s\S])*\*\/|[\r\n\t]+/g, '');
    // makes sure nextChar will not target a space
    rules = rules.replace(/}(\s*)@/g, '}@');
    rules = rules.replace(/}(\s*)}/g, '}}');
    for (let i = 0; i < rules.length - 2; i++) {
        char = rules[i];
        nextChar = rules[i + 1];
        if (char === '@' && nextChar !== 'f')
            isAt = true;
        if (!isAt && char === '{')
            isIn = true;
        if (isIn && char === '}')
            isIn = false;
        if (!isIn &&
            nextChar !== '@' &&
            nextChar !== '}' &&
            (char === '}' || char === ',' || ((char === '{' || char === ';') && isAt))) {
            rules = rules.slice(0, i + 1) + className + rules.slice(i + 1);
            i += classLen;
            isAt = false;
        }
    }
    // prefix the first select if it is not `@media` and if it is not yet prefixed
    if (rules.indexOf(className) !== 0 && rules.indexOf('@') !== 0)
        rules = className + rules;
    return rules;
};
const LIGHT_MODE_PREFIX = "html:not([data-theme='dark'])";
const DARK_MODE_PREFIX = "html([data-theme='dark'])";
function ServerStyles({ specProps, lightThemeOptions, darkThemeOptions, }) {
    const fullUrl = (0, useBaseUrl_1.default)(specProps.url, { absolute: true });
    const css = {
        light: '',
        dark: '',
    };
    const lightSheet = new styled_components_1.ServerStyleSheet();
    const lightStore = new redoc_1.AppStore(specProps.spec, fullUrl, lightThemeOptions);
    (0, server_1.renderToString)(lightSheet.collectStyles(react_1.default.createElement(redoc_1.Redoc, { store: lightStore })));
    const lightStyleTag = lightSheet.getStyleTags();
    let lightCss = lightStyleTag.slice(lightStyleTag.indexOf('>') + 1);
    lightCss = lightCss.slice(0, lightCss.indexOf('<style'));
    css.light = prefixCssSelectors(lightCss, LIGHT_MODE_PREFIX);
    const darkSheet = new styled_components_1.ServerStyleSheet();
    const darkStore = new redoc_1.AppStore(specProps.spec, fullUrl, darkThemeOptions);
    (0, server_1.renderToString)(darkSheet.collectStyles(react_1.default.createElement(redoc_1.Redoc, { store: darkStore })));
    const darkStyleTag = darkSheet.getStyleTags();
    let darkCss = darkStyleTag.slice(darkStyleTag.indexOf('>') + 1);
    darkCss = darkCss.slice(0, darkCss.indexOf('<style'));
    css.dark = prefixCssSelectors(darkCss, DARK_MODE_PREFIX).slice(DARK_MODE_PREFIX.length + 1);
    return (react_1.default.createElement("div", { className: "redocusaurus-styles" },
        react_1.default.createElement("style", { key: "light-mode-styles", dangerouslySetInnerHTML: { __html: css.light } }),
        react_1.default.createElement("style", { key: "dark-mode-styles", dangerouslySetInnerHTML: { __html: css.dark } })));
}
exports.ServerStyles = ServerStyles;
//# sourceMappingURL=ServerStyles.js.map