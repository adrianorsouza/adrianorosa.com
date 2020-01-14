/** ========================================================================
 * Project     : gatsby-markdown-starter
 * Component   : 404
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2019-11-09 19:24
 * ========================================================================
 * Copyright 2019 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

import React from 'react';
import { Link } from 'gatsby';
import Layout from '../components/layout';

export default props => {
  return (
    <>
      <Layout title="Page Not Found" robots={false}>
        <h2>Page Not Found</h2>
        <p>
          <Link to="/">Back to Home</Link>
        </p>
      </Layout>
    </>
  );
};
