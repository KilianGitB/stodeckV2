<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\ArticleRepository;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Services\PdfService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InvoiceRepository $invoiceRepository, ManagerRegistry $doctrine, ArticleRepository $articleRepository, CustomerRepository $customerRepository): Response
    {
        
        $entityManager = $doctrine->getManager();
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);
        $invoiceNbr = $invoiceRepository->countForInvoiceName();
        $invoiceName = 'fct'.$invoiceNbr;
        $requestAll = $request->request->all();

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceToCreate = new Invoice();
            $invoiceToCreate->setName($invoiceName);
            $customer = $customerRepository->findOneById($requestAll['invoice']['customer']);
            $invoiceToCreate->setCustomer($customer);
            
            $articles = $articleRepository->findOneById($requestAll['invoice']['article']);
            $invoiceToCreate->addArticle($articles);
            $date = new \DateTime($requestAll['invoice']['date']);
            $articles->setDestockingDate($date);
            $articles->setState('1');

            $invoiceToCreate->setDate($date);
            $entityManager->persist($invoiceToCreate);
            $entityManager->flush();

            return $this->render('invoice/invoicepdf.html.twig', [
                'invoice' => $invoiceToCreate,
            ]);
        }

        return $this->renderForm('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, InvoiceRepository $invoiceRepository): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceRepository->save($invoice, true);

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, InvoiceRepository $invoiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $invoiceRepository->remove($invoice, true);
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_invoice_pdf', methods: ['GET','POST'])]
    public function invoiceToPdf(Request $request, InvoiceRepository $invoiceRepository)
    {   
        $requestAll = $request->attributes->all();
        $invoiceId = $requestAll['id'];
        $invoice = $invoiceRepository->findOneById($invoiceId);
        $pdfService = new PdfService;
        $html = $this->render('invoice/invoiceGenerPdf.html.twig', [
           'invoice' => $invoice,
        ]);
        $pdfService->generPdfFile($html);
        return $this->render('invoice/invoicepdf.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}
