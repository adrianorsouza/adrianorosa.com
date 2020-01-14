/** ========================================================================
 * Project     : gatsby-markdown-starter
 * Component   : index
 * Author      : Adriano Rosa <https://adrianorosa.com>
 * Date        : 2019-11-09 15:08
 * ========================================================================
 * Copyright 2019 Adriano Rosa <https://adrianorosa.com>
 * ======================================================================== */

import React from 'react';
import Layout from '../components/layout';
import { graphql, Link, useStaticQuery } from 'gatsby';

export default props => {
  const posts = useStaticQuery(graphql`
    query {
      allMarkdownRemark {
        group(field: fields___category) {
          fieldValue
          nodes {
            id
            fields {
              title
              slug
            }
          }
        }
      }
    }
  `);

  return (
    <>
      <Layout title="Home">
        <h2>Table of Contents</h2>
        <ul>
          {posts.allMarkdownRemark.group.map(({ fieldValue, nodes }) => (
            <li key={fieldValue}>
              <h3>{fieldValue}</h3>
              <ul>
                {nodes.map(({ id, fields }) => (
                  <li key={id}>
                    <Link to={fields.slug}>{fields.title}</Link>
                  </li>
                ))}
              </ul>
            </li>
          ))}
        </ul>
      </Layout>
    </>
  );
};
