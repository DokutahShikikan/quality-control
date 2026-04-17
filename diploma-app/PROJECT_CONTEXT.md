# Project Context

  ## Project
  Laravel diploma project for data quality control system.

  Working directory:
  `C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app`

  Template source:
  `Z:\Herd\learning`

  Important:
  - Original template in `Z:\Herd\learning` must not be modified.
  - Actual work must be done only inside `diploma-app`.

  ## Current status
  - A new Laravel project skeleton was copied into `diploma-app`.
  - Heavy directories and runtime junk were intentionally excluded or cleaned:
    - `vendor`
    - `node_modules`
    - `.env`
    - logs / caches
  - Project is not yet installed/runnable.
  - No need to explain the whole diploma idea again from scratch.

  ## Diploma topic
  System for data quality control:
  - validation
  - deduplication
  - consistency checks
  - issue tracking
  - reporting

  ## Expected architecture direction
  Base entities planned:
  - Dataset
  - Rule
  - CheckRun
  - Issue
  - DuplicateCandidate

  ## Roles
  There are two roles:
  - worker/user
  - admin/developer

  Current idea for separation:
  - worker: uploads datasets, runs checks, views issues/reports for own datasets
  - admin: manages global rules, dictionaries, users, and can view all runs/results

  ## Template interpretation
  The source template already has:
  - auth
  - a simple CRUD example with `Idea`
  - policies
  - blade views

  This CRUD should be reworked into diploma domain, starting from replacing `Idea` with something like `Dataset`.

  ## What should be done next
  Preferred next step:
  - prepare the Laravel project to runnable state inside `diploma-app`
  - then replace template CRUD with `Dataset`
  - then add the rest of the diploma modules

  ## Additional note
  The user does not want to repeatedly re-explain context.
  The assistant should continue from this state directly.