/** ========================================================================
 * Project     : gatsby-markdown-starter
 * Component   : header
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2019-11-09 15:31
 * ========================================================================
 * Copyright 2019 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

import React from 'react';
import { Link } from 'gatsby';

const Header = ({ siteMetadata }) => {
  return (
    <header>
      <h1>
        <Link to={`/`}>
          {siteMetadata.name} <small>{siteMetadata.description}</small>
        </Link>
      </h1>
      <p>
        <Link to={`/`}>Home</Link>
      </p>
    </header>
  );
};

export default Header;
