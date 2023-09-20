import React from 'react';
import ComponentCreator from '@docusaurus/ComponentCreator';

export default [
  {
    path: '/examples/',
    component: ComponentCreator('/examples/', '942'),
    exact: true
  },
  {
    path: '/examples/client-only/',
    component: ComponentCreator('/examples/client-only/', 'd8b'),
    exact: true
  },
  {
    path: '/openapi/got/',
    component: ComponentCreator('/openapi/got/', '7da'),
    exact: true
  },
  {
    path: '/docs',
    component: ComponentCreator('/docs', '1b4'),
    routes: [
      {
        path: '/docs/',
        component: ComponentCreator('/docs/', '08d'),
        exact: true,
        sidebar: "defaultSidebar"
      },
      {
        path: '/docs/events',
        component: ComponentCreator('/docs/events', '6dd'),
        exact: true,
        sidebar: "defaultSidebar"
      },
      {
        path: '/docs/used_technology',
        component: ComponentCreator('/docs/used_technology', 'f80'),
        exact: true,
        sidebar: "defaultSidebar"
      }
    ]
  },
  {
    path: '/',
    component: ComponentCreator('/', 'e90'),
    exact: true
  },
  {
    path: '*',
    component: ComponentCreator('*'),
  },
];
