"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.getGlobalData = exports.getRedocThemes = void 0;
const merge_1 = __importDefault(require("lodash/merge"));
const openapi_core_1 = require("@redocly/openapi-core");
const defaultOptions = {
    scrollYOffset: 'nav.navbar',
    expandSingleSchemaField: true,
    menuToggle: true,
    // @ts-expect-error not available in types
    suppressWarnings: true,
};
const getDefaultTheme = (primaryColor, customTheme) => {
    return (0, merge_1.default)({
        colors: {
            primary: {
                main: primaryColor || '#25c2a0',
            },
        },
        theme: {
            prism: {
                additionalLanguages: ['scala'],
            },
        },
    }, customTheme);
};
/**
 * TODO: Update colors from infima
 * @see https://github.com/facebookincubator/infima/blob/master/packages/core/styles/common/variables.pcss
 */
const DOCUSAURUS = {
    darkGray: '#303846',
    dark: {
        primaryText: '#f5f6f7',
        secondaryText: 'rgba(255, 255, 255, 1)',
        backgroundColor: 'rgb(24, 25, 26)',
    },
};
/**
 * Theme override that is independant of Light/Black themes
 */
const COMMON_THEME = {
    typography: {
        fontFamily: 'var(--ifm-font-family-base)',
        fontSize: 'var(--ifm-font-size-base)',
        lineHeight: 'var(--ifm-line-height-base)',
        fontWeightLight: 'var(--ifm-font-weight-light)',
        fontWeightRegular: 'var(--ifm-font-weight-base)',
        fontWeightBold: 'var(--ifm-font-weight-bold)',
        headings: {
            fontFamily: 'var(--ifm-heading-font-family)',
            fontWeight: 'var(--ifm-heading-font-weight)',
            lineHeight: 'var(--ifm-heading-line-height)',
        },
        code: {
            fontFamily: 'var(--ifm-font-family-monospace)',
            lineHeight: 'var(--ifm-pre-line-height)',
        },
    },
    sidebar: {
        /**
         * about the same as the sidebar in the docs area, for consistency
         * @see https://davidgoss.co/blog/api-documentation-redoc-docusaurus/
         */
        width: '300px',
    },
};
/**
 * NOTE: Variables taken from infima
 * @see https://github.com/facebookincubator/infima/blob/master/packages/core/styles/common/variables.pcss
 */
const LIGHT_THEME_OPTIONS = {
    sidebar: {
        backgroundColor: '#ffffff',
    },
    rightPanel: {
        backgroundColor: DOCUSAURUS.darkGray,
    },
};
const DARK_THEME_OPTIONS = {
    colors: {
        text: {
            primary: DOCUSAURUS.dark.primaryText,
            secondary: DOCUSAURUS.dark.secondaryText,
        },
        gray: {
            50: '#FAFAFA',
            100: '#F5F5F5',
        },
        border: {
            dark: '#ffffff',
            light: 'rgba(0,0,0, 0.1)',
        },
    },
    schema: {
        nestedBackground: DOCUSAURUS.dark.backgroundColor,
        typeNameColor: DOCUSAURUS.dark.secondaryText,
        typeTitleColor: DOCUSAURUS.dark.secondaryText,
    },
    sidebar: {
        backgroundColor: DOCUSAURUS.dark.backgroundColor,
        textColor: DOCUSAURUS.dark.primaryText,
        arrow: {
            color: DOCUSAURUS.dark.primaryText,
        },
    },
};
function getThemeOptions(customTheme, isDarkMode = false) {
    if (isDarkMode) {
        return (0, merge_1.default)({}, COMMON_THEME, DARK_THEME_OPTIONS, customTheme);
    }
    else {
        return (0, merge_1.default)({}, COMMON_THEME, LIGHT_THEME_OPTIONS, customTheme);
    }
}
function getRedocThemes(customTheme, customDarkTheme = customTheme) {
    return {
        lightTheme: getThemeOptions(customTheme, false),
        darkTheme: getThemeOptions(customDarkTheme, true),
    };
}
exports.getRedocThemes = getRedocThemes;
async function getGlobalData({ primaryColor, primaryColorDark = primaryColor, theme: customThemeDeprecated, options, }) {
    let redoclyOptions;
    if (options) {
        if (typeof options === 'string') {
            redoclyOptions = (await (0, openapi_core_1.loadConfig)({
                configPath: options,
            })).theme.openapi;
        }
        else {
            redoclyOptions = new openapi_core_1.Config({
                theme: {
                    openapi: options,
                },
                // eslint-disable-next-line @typescript-eslint/no-explicit-any
            }).theme.openapi;
        }
    }
    else {
        redoclyOptions = (await (0, openapi_core_1.loadConfig)()).theme.openapi;
    }
    const customTheme = {
        ...redoclyOptions === null || redoclyOptions === void 0 ? void 0 : redoclyOptions.theme,
        ...customThemeDeprecated,
    };
    const overrides = getDefaultTheme(primaryColor, customTheme);
    const overridesDark = getDefaultTheme(primaryColorDark, customTheme);
    const { lightTheme, darkTheme } = getRedocThemes(overrides, overridesDark);
    return {
        lightTheme,
        darkTheme,
        options: {
            ...defaultOptions,
            ...redoclyOptions,
        },
    };
}
exports.getGlobalData = getGlobalData;
//# sourceMappingURL=redocData.js.map