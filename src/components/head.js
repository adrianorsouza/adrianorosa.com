/** ========================================================================
 * Project     : gatsby-markdown-starter
 * Component   : head
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2019-11-09 18:44
 * ========================================================================
 * Copyright 2019 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

import React from 'react';
import { Helmet } from 'react-helmet';

const Head = ({ siteMetadata, children }) => {
  return (
    <Helmet titleTemplate={`%s | ${siteMetadata.author}`}>{children}</Helmet>
  );
};

export default Head;
