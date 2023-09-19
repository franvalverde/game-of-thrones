const  path = require('path');

/**
 * @type {import('redocusaurus').PresetEntry}
 */
const redocusaurus = [
  'redocusaurus',
  {
    debug: Boolean(process.env.DEBUG || process.env.CI),
    config: path.join(__dirname, 'redocly.yaml'),
    specs: [
      {
        id: 'using-single-yaml',
        spec: 'openapi/single-file/openapi.yaml',
        route: '/examples/using-single-yaml/',
      },
      {
        id: 'using-multi-file-yaml',
        spec: 'openapi/multi-file/openapi.yaml',
        route: '/examples/using-multi-file-yaml/',
      },
      {
        id: 'openapi-got',
        spec: 'openapi/swagger/swagger.json',
        route: '/openapi/got/',
      },
      {
        id: 'using-swagger-json',
        spec: 'openapi/swagger/swagger.json',
        route: '/examples/using-swagger-json/',
      },
      {
        id: 'using-remote-url',
        // Remote File
        spec: 'https://redocly.github.io/redoc/openapi.yaml',
        route: '/examples/using-remote-url/',
      },
      {
        id: 'using-custom-page',
        spec: 'openapi/single-file/openapi.yaml',
        // NOTE: no `route` passed, instead data used in custom React Component ('custom-page/index.jsx')
      },
      {
        id: 'using-custom-layout',
        spec: 'openapi/single-file/openapi.yaml',
        // NOTE: no `route` passed, instead data used in custom React Component ('custom-layout/index.jsx')
      },
    ],
    theme: {
      /**
       * Highlight color for docs
       */
      primaryColor: '#1890ff',
    },
  },
];

if (process.env.VERCEL_URL) {
  process.env.DEPLOY_PRIME_URL = `https://${process.env.VERCEL_URL}`;
}

/**
 * @type {Partial<import('@docusaurus/types').DocusaurusConfig>}
 */
const config = {
  presets: [
    /** ************ Your other presets' config  *********** */
    [
      '@docusaurus/preset-classic',
      {
        debug: Boolean(process.env.DEBUG || process.env.CI),
        theme: { customCss: [require.resolve('./src/custom.css')] },
        docs: {
          routeBasePath: '/docs',
          editUrl: 'https://github.com/rohit-gohri/redocusaurus/edit/main/website/',
        },
      },  
    ],
    // Redocusaurus Config
    redocusaurus,
  ],
  title: 'Fran Valverde',
  tagline: 'Docusaurus docs with Redoc',
  customFields: {
    meta: {
      description: 'Integrate Redoc easily into your Docusaurus Site',
    },
  },
  url: process.env.DEPLOY_PRIME_URL || 'http://localhost:3000', // Your website URL
  baseUrl: process.env.DEPLOY_BASE_URL || '/', // Base URL for your project */
  favicon: 'img/favicon.ico',
  themeConfig: {
    navbar: {
      title: 'Home',
      items: [
        {
          label: 'Docs',
          position: 'left',
          to: '/docs',
        },
        {
          label: 'OpenApi',
          position: 'left',
          to: '/examples/using-swagger-json/',
        },
      ],
    },
    footer: {
      style: 'dark',
      copyright: `Copyright © ${new Date().getFullYear()} Fran Valverde. Built with <a href="https://github.com/facebook/docusaurus" target="_blank" rel="noopener noreferrer">Docusaurus</a>`,
    },
  },
};

module.exports = config;
