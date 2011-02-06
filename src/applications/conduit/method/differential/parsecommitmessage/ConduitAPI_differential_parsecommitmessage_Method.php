<?php

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class ConduitAPI_differential_parsecommitmessage_Method
  extends ConduitAPIMethod {

  public function getMethodDescription() {
    return "Parse commit messages for Differential fields.";
  }

  public function defineParamTypes() {
    return array(
      'corpus' => 'required string',
    );
  }

  public function defineReturnType() {
    return 'nonempty dict';
  }

  public function defineErrorTypes() {
    return array(
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $corpus = $request->getValue('corpus');

    try {
      $message = DifferentialCommitMessage::newFromRawCorpus($corpus);
    } catch (DifferentialCommitMessageParserException $ex) {
      return array(
        'error' => $ex->getMessage(),
      );
    }

    return array(
      'error' => null,
      'fields' => array(
        'title'             => $message->getTitle(),
        'summary'           => $message->getSummary(),
        'testPlan'          => $message->getTestPlan(),
        'blameRevision'     => $message->getBlameRevision(),
        'revertPlan'        => $message->getRevertPlan(),
        'reviewerPHIDs'     => $message->getReviewerPHIDs(),
        'reviewedByPHIDs'   => $message->getReviewedByPHIDs(),
        'ccPHIDs'           => $message->getCCPHIDs(),
        'revisionID'        => $message->getRevisionID(),
        'gitSVNID'          => $message->getGitSVNID(),
      ),
    );
  }
}
