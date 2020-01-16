/** ========================================================================
 * Project     : gatsby-markdown-starter
 * Component   : layout
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2019-11-09 15:32
 * ========================================================================
 * Copyright 2019 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

import React from 'react';
import Header from './header';
import Footer from './footer';
import PropTypes from 'prop-types';
import Head from './head';
import { graphql, useStaticQuery } from 'gatsby';

const Layout = props => {
  const { title, description, robots } = props;

  const { site } = useStaticQuery(graphql`
    query {
      site {
        siteMetadata {
          title
          author {
           name
          }
          description
        }
      }
    }
  `);

  const { siteMetadata } = site;

  return (
    <>
      <Head siteMetadata={siteMetadata}>
        <title>{title}</title>
        {description && <meta name="description" content={description} />}
        {(robots === false && (
          <meta name="robots" content="noindex, nofollow" />
        )) ||
          ``}
      </Head>
      <main>
        <Header {...props} siteMetadata={siteMetadata} />
        <article>{props.children}</article>
        <Footer />
      </main>
    </>
  );
};

Layout.propTypes = {
  title: PropTypes.string.isRequired,
  description: PropTypes.string,
  robots: PropTypes.bool,
};

export default Layout;
